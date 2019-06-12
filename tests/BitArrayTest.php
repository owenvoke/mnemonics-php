<?php

declare(strict_types=1);

namespace pxgamer\Mnemonics\Tests;

use function hex2bin;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use pxgamer\Mnemonics\BitArray;

final class BitArrayTest extends TestCase
{
    /** @test */
    public function itCanConvertBytesToABitArray(): void
    {
        $str = hex2bin('11');
        $bitArray = new BitArray($str);

        $this->assertCount(8, $bitArray);

        $this->assertEquals(0, $bitArray[0]);
        $this->assertEquals(0, $bitArray[1]);
        $this->assertEquals(0, $bitArray[2]);
        $this->assertEquals(1, $bitArray[3]);
        $this->assertEquals(0, $bitArray[4]);
        $this->assertEquals(0, $bitArray[5]);
        $this->assertEquals(0, $bitArray[6]);
        $this->assertEquals(1, $bitArray[7]);

        $this->assertEquals($str, $bitArray->toBytes());
    }

    /** @test */
    public function itCanConvertABitArrayToBytes(): void
    {
        $bitArray = new BitArray([1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 1]);
        $this->assertEquals(hex2bin('c1c1'), $bitArray->toBytes());
    }

    /** @test */
    public function itCanSliceABitArray(): void
    {
        $str = hex2bin('1111');
        $bitArray = new BitArray($str);
        $bitArray = $bitArray->slice(0, 8);

        $this->assertEquals(hex2bin('11'), $bitArray->toBytes());
    }

    /** @test */
    public function itCanIterateOverABitArray(): void
    {
        $str = hex2bin('ffff');
        $bitArray = new BitArray($str);

        foreach ($bitArray as $bit) {
            $this->assertTrue($bit);
        }
    }

    /** @test */
    public function itCanMergeTwoBitArrays(): void
    {
        $bitArray1 = new BitArray(hex2bin('ff'));
        $bitArray2 = new BitArray(hex2bin('ff'));

        $bitArray3 = $bitArray1->merge($bitArray2);

        $this->assertEquals(hex2bin('ffff'), $bitArray3->toBytes());
    }

    /**
     * @test
     */
    public function itThrowsAnExceptionOnAnInvalidBitArray(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new BitArray([0, 1, 0, 1, 2, 1, 1]);
    }
}
