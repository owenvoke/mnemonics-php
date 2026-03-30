<?php

declare(strict_types=1);

namespace OwenVoke\Mnemonics\Tests;

use InvalidArgumentException;
use OwenVoke\Mnemonics\BitArray;

it('can convert bytes to a bit array', function () {
    $str = hex2bin('11');
    assert(is_string($str));

    $bitArray = new BitArray($str);

    expect($bitArray->toArray())
        ->toHaveCount(8)
        ->{0}->toEqual(0)
        ->{1}->toEqual(0)
        ->{2}->toEqual(0)
        ->{3}->toEqual(1)
        ->{4}->toEqual(0)
        ->{5}->toEqual(0)
        ->{6}->toEqual(0)
        ->{7}->toEqual(1)
        ->and($bitArray->toBytes())->toEqual($str);

});

it('can convert a bit array to bytes', function () {
    $bitArray = new BitArray([1, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 0, 0, 0, 0, 1]);

    expect($bitArray->toBytes())
        ->toEqual(hex2bin('c1c1'));
});

it('can slice a bit array', function () {
    $str = hex2bin('1111');
    assert(is_string($str));

    $bitArray = new BitArray($str);
    $bitArray = $bitArray->slice(0, 8);

    expect($bitArray->toBytes())
        ->toEqual(hex2bin('11'));
});

it('can iterate over a bit array', function () {
    $str = hex2bin('ffff');
    assert(is_string($str));

    $bitArray = new BitArray($str);

    foreach ($bitArray as $bit) {
        expect($bit)->toEqual(1);
    }
});

it('can merge two bit arrays', function () {
    $str = hex2bin('ff');
    assert(is_string($str));

    $bitArray1 = new BitArray($str);
    $bitArray2 = new BitArray($str);

    $bitArray3 = $bitArray1->merge($bitArray2);

    expect($bitArray3->toBytes())
        ->toEqual(hex2bin('ffff'));
});

it('throws an exception on an invalid bit array', function () {
    new BitArray([0, 1, 0, 1, 2, 1, 1]);
})->throws(InvalidArgumentException::class);
