<?php

declare(strict_types=1);

namespace OwenVoke\Mnemonics\Tests;

use OwenVoke\Mnemonics\DefaultWordList;
use OwenVoke\Mnemonics\Mnemonic;

beforeEach(fn () => $this->mnemonic = (new Mnemonic(DefaultWordList::WORDS)));

it('can convert an entropy string to a mnemonic sequence', function () {
    // 96 bits
    $mnemonics = $this->mnemonic->toMnemonic('abc123hdghaj');

    expect($mnemonics)
        ->{0}->toEqual('gesture')
        ->{1}->toEqual('basic')
        ->{2}->toEqual('slush')
        ->{3}->toEqual('good')
        ->{4}->toEqual('custom')
        ->{5}->toEqual('cram')
        ->{6}->toEqual('oval')
        ->{7}->toEqual('around')
        ->{8}->toEqual('height');
});

it('can convert a mnemonic sequence to an entropy string', function () {
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

    expect($entropy)->toEqual('abc123hdghaj');
});

it('can create a mnemonic instance using the maker method', function () {
    expect(Mnemonic::make())->toBeInstanceOf(Mnemonic::class);
});
