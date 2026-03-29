<?php

// Include Composer's autoloader
require '../vendor/autoload.php';

use OwenVoke\Mnemonics\Mnemonic;
use OwenVoke\Mnemonics\DefaultWordList;

// Start the session
session_start();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = array(); // Unset all session variables
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/'); // Delete session cookie
    }
    session_destroy(); // Destroy the session
    header('Location: ?sign=in'); // Redirect to login
    exit;
}

// Initialize the mnemonic generator
$mnemonicGenerator = new Mnemonic(DefaultWordList::WORDS);

// Define a simple user store (in a real app, this would be a database)
$userStore = __DIR__ . '/users.json';

// Helper function to load users
function loadUsers($file) {
    return file_exists($file) ? json_decode(file_get_contents($file), true) : [];
}

// Helper function to save users
function saveUsers($file, $users) {
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// Handle sign-up
if (isset($_GET['sign']) && $_GET['sign'] === 'up') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
        $username = trim($_POST['username']);
        if (empty($username)) {
            $error = "Username is required.";
        } else {
            $users = loadUsers($GLOBALS['userStore']);
            if (isset($users[$username])) {
                $error = "Username already exists.";
            } else {
                // Generate secure entropy (128 bits for a 12-word phrase)
                $entropy = random_bytes(16);
                // Convert entropy to a mnemonic phrase
                $mnemonicPhrase = $mnemonicGenerator->toMnemonic($entropy);
                // Store the user with a hash of their entropy (for verification)
                $users[$username] = [
                    'entropy_hash' => hash('sha256', $entropy),
//                    'mnemonic' => $mnemonicPhrase
                ];
                saveUsers($GLOBALS['userStore'], $users);

                $numbers = [];
                foreach (range(1, count($mnemonicPhrase)) as $number) {
                    $numbers[] .= $number;
                }
                $success = "Account created! Your recovery phrase is:<br><table><tr><td><pre><code>" . implode('<br>',$numbers) . "</code></pre></td><td><pre><code id=\"mn\">" . implode('<br>',$mnemonicPhrase) . "</code></pre></td></tr></table><br><button id=\"cb\" onclick=\"copyHtmlToClipboard(document.getElementById('mn'));\">Copy Content</button>   <br>Write this down and keep it safe!";
            }
        }
    }
    // Show sign-up form
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Sign Up</title></head>
    <body>
        <h2>Create an Account</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color:green;"><?= $success ?></p>
            <script>
             function copyHtmlToClipboard(element) {
               const button = document.getElementById('cb');
               const originalText = button.textContent;

               const textArea = document.createElement('textarea');
               textArea.style.position = 'fixed';
               textArea.style.left = '-9999px';
               textArea.value = element.innerHTML.replace(/<\s*\/?br\s*[\/]?>/gi, '\n');
               
               document.body.appendChild(textArea);
               textArea.select();
               

               try {
                 const successful = document.execCommand('copy');
                 if (successful) {
                   button.textContent = 'Copied!';
                   setTimeout(() => {
                     button.textContent = originalText;
                   }, 2000);
                 }
               } catch (err) {
                 console.error('Oops, unable to copy', err);
               } finally {
                 document.body.removeChild(textArea);
               }
             }
             // copyHtmlToClipboard(document.getElementById('mn'));
            </script>
        <?php else: ?>
            <form method="post">
                <label>Username: <input type="text" name="username" required></label><br><br>
                <button type="submit">Sign Up</button>
            </form>
            <p><a href="?sign=in">Already have an account? Sign In</a></p>
        <?php endif; ?>
    </body>
    </html>
    <?php
    exit;
}

// Handle sign-in
if (isset($_GET['sign']) && $_GET['sign'] === 'in') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['mnemonic'])) {
        $username = trim($_POST['username']);
        $inputMnemonic = trim($_POST['mnemonic']);
        $words = preg_split('/[\s,]+/', $inputMnemonic, -1, PREG_SPLIT_NO_EMPTY);   

        $users = loadUsers($GLOBALS['userStore']);
        if (!isset($users[$username])) {
            $error = "Invalid username or recovery phrase.";
        } else {
            try {
                // Convert the input mnemonic phrase back to entropy
                $recoveredEntropy = $mnemonicGenerator->toEntropy($words);
                // Verify the recovered entropy matches the stored hash
                if (hash_equals($users[$username]['entropy_hash'], hash('sha256', $recoveredEntropy))) {
                    // Authentication successful
                    $_SESSION['authenticated'] = true;
                    $_SESSION['username'] = $username;
                    header('Location: ?');
                    exit;
                } else {
                    $error = "Invalid username or recovery phrase.";
                }
            } catch (Exception $e) {
                $error = "Invalid recovery phrase.";
            }
        }
    }

    // Show sign-in form
    ?>
    <!DOCTYPE html>
    <html>
    <head><title>Sign In</title></head>
    <body>
        <h2>Sign In</h2>
        <?php if (isset($error)): ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post">
            <label>Username: <input type="text" name="username" required></label><br><br>
            <label>Recovery Phrase:<br>
                <textarea name="mnemonic" rows="3" cols="50" placeholder="Enter your 12-word recovery phrase" required></textarea>
            </label><br><br>
            <button type="submit">Sign In</button>
        </form>
        <p><a href="?sign=up">Don't have an account? Sign Up</a></p>
    </body>
    </html>
    <?php
    exit;
}

// Main page (protected)
if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
    header('Location: ?sign=in');
    exit;
}

// User is logged in
echo "<h1>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
echo "<p>You are now logged in.</p>";
echo '<a href="?action=logout">Log Out</a>';
?>
