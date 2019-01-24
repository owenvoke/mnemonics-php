<?php

declare(strict_types=1);

namespace pxgamer\Mnemonics\Test;

use PHPUnit\Framework\TestCase;
use pxgamer\Mnemonics\DefaultWordList;
use pxgamer\Mnemonics\Mnemonic;

class MnemonicTest extends TestCase
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

        $this->assertThat($mnemonics[0], $this->equalTo('gesture'));
        $this->assertThat($mnemonics[1], $this->equalTo('basic'));
        $this->assertThat($mnemonics[2], $this->equalTo('slush'));
        $this->assertThat($mnemonics[3], $this->equalTo('good'));
        $this->assertThat($mnemonics[4], $this->equalTo('custom'));
        $this->assertThat($mnemonics[5], $this->equalTo('cram'));
        $this->assertThat($mnemonics[6], $this->equalTo('oval'));
        $this->assertThat($mnemonics[7], $this->equalTo('around'));
        $this->assertThat($mnemonics[8], $this->equalTo('height'));
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

        $this->assertThat($entropy, $this->equalTo('abc123hdghaj'));
    }
}
