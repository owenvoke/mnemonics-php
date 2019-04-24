<?php

declare(strict_types=1);

namespace pxgamer\Mnemonics\Test;

use PHPUnit\Framework\TestCase;
use pxgamer\Mnemonics\DefaultWordList;
use pxgamer\Mnemonics\Mnemonic;

final class MnemonicTest extends TestCase
{
    /** @var Mnemonic */
    private $mnemonic;

    public function setUp(): void
    {
        $this->mnemonic = new Mnemonic(DefaultWordList::WORDS);
    }

    /** @test */
    public function itCanConvertEntropyToAMnemonicSequence(): void
    {
        // 96 bits
        $mnemonics = $this->mnemonic->toMnemonic('abc123hdghaj');

        $this->assertEquals('gesture', $mnemonics[0]);
        $this->assertEquals('basic', $mnemonics[1]);
        $this->assertEquals('slush', $mnemonics[2]);
        $this->assertEquals('good', $mnemonics[3]);
        $this->assertEquals('custom', $mnemonics[4]);
        $this->assertEquals('cram', $mnemonics[5]);
        $this->assertEquals('oval', $mnemonics[6]);
        $this->assertEquals('around', $mnemonics[7]);
        $this->assertEquals('height', $mnemonics[8]);
    }

    /** @test */
    public function itCanConvertAMnemonicSequenceToEntropy(): void
    {
        $entropy = $this->mnemonic->toEntropy([
            'gesture',
            'basic',
            'slush',
            'good',
            'custom',
            'cram',
            'oval',
            'around',
            'height',
        ]);

        $this->assertEquals('abc123hdghaj', $entropy);
    }
}
