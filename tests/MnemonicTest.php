<?php

declare(strict_types=1);

namespace OwenVoke\Mnemonics\Tests;

use OwenVoke\Mnemonics\DefaultWordList;
use OwenVoke\Mnemonics\Mnemonic;

beforeEach(fn () => $this->mnemonic = (new Mnemonic(DefaultWordList::WORDS)));

it('can convert an entropy string to a mnemonic sequence', function () {
    // 96 bits
    $mnemonics = $this->mnemonic->toMnemonic('abc123hdghaj');

    expect($mnemonics[0])->toEqual('gesture');
    expect($mnemonics[1])->toEqual('basic');
    expect($mnemonics[2])->toEqual('slush');
    expect($mnemonics[3])->toEqual('good');
    expect($mnemonics[4])->toEqual('custom');
    expect($mnemonics[5])->toEqual('cram');
    expect($mnemonics[6])->toEqual('oval');
    expect($mnemonics[7])->toEqual('around');
    expect($mnemonics[8])->toEqual('height');
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
