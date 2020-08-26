<?php

declare(strict_types=1);

namespace OwenVoke\Mnemonics\Test;

use PHPUnit\Framework\TestCase;
use OwenVoke\Mnemonics\BitArray;

class BitArrayTest extends TestCase
{
    /** @test */
    public function itCanConvertBytesToABitArray(): void
    {
        $str = hex2bin('11');
        $bitArray = new BitArray($str);

        $this->assertThat(count($bitArray), $this->equalTo(8));
        $this->assertThat($bitArray[0], $this->equalTo(0));
        $this->assertThat($bitArray[1], $this->equalTo(0));
        $this->assertThat($bitArray[2], $this->equalTo(0));
        $this->assertThat($bitArray[3], $this->equalTo(1));
        $this->assertThat($bitArray[4], $this->equalTo(0));
        $this->assertThat($bitArray[5], $this->equalTo(0));
        $this->assertThat($bitArray[6], $this->equalTo(0));
        $this->assertThat($bitArray[7], $this->equalTo(1));

        $this->assertThat($bitArray->toBytes(), $this->equalTo($str));
    }

    /** @test */
    public function itCanConvertABitArrayToBytes(): void
    {
        $bitArray = new BitArray([1,1,0,0,0,0,0,1,1,1,0,0,0,0,0,1]);
        $this->assertThat($bitArray->toBytes(), $this->equalTo(hex2bin('c1c1')));
    }

    /** @test */
    public function itCanSliceABitArray(): void
    {
        $str = hex2bin('1111');
        $bitArray = new BitArray($str);
        $bitArray = $bitArray->slice(0, 8);

        $this->assertThat($bitArray->toBytes(), $this->equalTo(hex2bin('11')));
    }

    /** @test */
    public function itCanIterateOverABitArray(): void
    {
        $str = hex2bin('ffff');
        $bitArray = new BitArray($str);

        foreach ($bitArray as $bit) {
            $this->assertThat($bit, $this->isTrue());
        }
    }

    /** @test */
    public function itCanMergeTwoBitArrays(): void
    {
        $bitArray1 = new BitArray(hex2bin('ff'));
        $bitArray2 = new BitArray(hex2bin('ff'));

        $bitArray3 = $bitArray1->merge($bitArray2);

        $this->assertThat($bitArray3->toBytes(), $this->equalTo(hex2bin('ffff')));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function itThrowsAnExceptionOnAnInvalidBitArray(): void
    {
        new BitArray([0,1,0,1,2,1,1]);
    }
}
