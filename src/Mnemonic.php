<?php

declare(strict_types=1);

namespace OwenVoke\Mnemonics;

use RuntimeException;

class Mnemonic
{
    public const ENTROPY_BITS = 128;

    /** @var array<int, string> */
    private array $words;

    /** @param array<int, string> $wordList */
    public function __construct(array $wordList)
    {
        if (count($wordList) !== 2048) {
            throw new RuntimeException('Invalid number of mnemonic words provided. Must be 2048.');
        }

        $this->words = $wordList;
    }

    /** @return array<int, string> */
    public function toMnemonic(string $entropy): array
    {
        // Entropy should be in 32 bit (4 byte) multiples
        if (strlen($entropy) % 4 !== 0) {
            throw new RuntimeException('Entropy bits must be divisible by 32');
        }

        $hash = hash('sha256', $entropy, true);
        $entropyBits = new BitArray($entropy);
        $hashBits = new BitArray($hash);
        $checksumLen = count($entropyBits) / 32;

        $concatBits = $entropyBits->merge($hashBits->slice(0, $checksumLen));

        // Split into 11 bit chunks, encoding numbers 0-2047, as index spots in a word list
        $words = [];
        $numWords = count($concatBits) / 11;
        for ($i = 0; $i < $numWords; $i++) {
            $slice = $concatBits->slice($i * 11, 11);
            $index = 0;
            for ($j = 0; $j < count($slice); $j++) {
                $index <<= 1;
                if ($slice[$j]) {
                    $index |= 0x01;
                }
            }

            $words[] = $this->words[$index];
        }

        return $words;
    }

    /** @param array<int, string> $words */
    public function toEntropy(array $words): string
    {
        // Collect up words, compact into bit array
        $temporaryBitArray = new BitArray([]);

        for ($i = 0; $i < count($words); $i++) {
            $wordIndex = array_search($words[$i], $this->words, true);
            for ($j = 0; $j < 11; $j++) {
                $temporaryBitArray[($i * 11) + $j] = (($wordIndex & (1 << 10 - $j)) !== 0);
            }
        }

        $concatLenBits = count($temporaryBitArray);
        $checksumLengthBits = $concatLenBits / 33;
        $entropyLengthBits = $concatLenBits - $checksumLengthBits;

        $entropy = $temporaryBitArray->slice(0, $entropyLengthBits)->toBytes();
        $hashBits = new BitArray(hash('sha256', $entropy, true));
        $checksum = $temporaryBitArray->slice($entropyLengthBits, $checksumLengthBits);

        if ($checksum->toArray() !== $hashBits->slice(0, $checksumLengthBits)->toArray()) {
            throw new RuntimeException('Invalid checksum');
        }

        return $entropy;
    }
}
