<?php

declare(strict_types=1);

namespace pxgamer\Mnemonics;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;

final class BitArray implements ArrayAccess, Countable, Iterator
{
    private $bits = [];

    public function __construct($bytes)
    {
        if (is_string($bytes)) {
            $this->bits = self::bytesToBits($bytes);
        }

        if (is_array($bytes)) {
            // Check all bytes are actually 1 or 0
            $invalidBytes = array_filter($bytes, static function ($v) {
                return ($v > 1) || ($v < 0);
            });

            if (count($invalidBytes) > 0) {
                throw new InvalidArgumentException('Array argument contains values other than 0,1,true,false');
            }

            $this->bits = $bytes;
        }
    }

    public function toBytes(): string
    {
        return $this->__toString();
    }

    public function __toString(): string
    {
        $str = '';

        for ($i = 0; $i < count($this->bits) / 8; $i++) {
            $slice = array_slice($this->bits, $i * 8, 8);
            $index = 0;

            foreach ($slice as $j => $jValue) {
                $index <<= 1;
                if ($slice[$j]) {
                    $index |= 0x01;
                }
            }

            $str .= chr($index);
        }

        return $str;
    }

    private static function bytesToBits($bytes): array
    {
        $bits = [];
        $numBytes = strlen($bytes);

        for ($i = 0; $i < $numBytes; $i++) {
            $byte = ord($bytes[$i]);
            for ($j = 7; $j >= 0; $j--) {
                $idx = 0x01 << $j;
                $bits[] = (($idx & $byte) === $idx);
            }
        }

        return $bits;
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->bits);
    }

    public function offsetGet($offset)
    {
        return $this->bits[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->bits[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->bits[$offset]);
    }

    public function count(): int
    {
        return count($this->bits);
    }

    public function slice($offset, $length): self
    {
        return new self(array_slice($this->bits, $offset, $length));
    }

    public function current()
    {
        return current($this->bits);
    }

    public function next()
    {
        return next($this->bits);
    }

    public function key()
    {
        return key($this->bits);
    }

    public function valid(): bool
    {
        return $this->key() !== null;
    }

    public function rewind(): void
    {
        reset($this->bits);
    }

    public function merge(BitArray $bitArray2): self
    {
        return new self(array_merge($this->bits, $bitArray2->toArray()));
    }

    public function toArray(): array
    {
        return $this->bits;
    }
}
