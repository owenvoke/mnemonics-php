<?php

declare(strict_types=1);

namespace OwenVoke\Mnemonics;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;

/**
 * @implements ArrayAccess<int, bool|int>
 * @implements Iterator<int, bool|int>
 */
final class BitArray implements ArrayAccess, Countable, Iterator
{
    /** @var array<int, bool|int> */
    private array $bits = [];

    /** @param string|array<int, bool|int> $bytes */
    public function __construct(array|string $bytes)
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

            foreach ($slice as $value) {
                $index <<= 1;
                if ($value) {
                    $index |= 0x01;
                }
            }

            $str .= chr($index);
        }

        return $str;
    }

    /** @return array<int, bool|int> */
    private static function bytesToBits(string $bytes): array
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

    /** @param int $offset */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->bits);
    }

    /**
     * @param  int  $offset
     */
    public function offsetGet(mixed $offset): bool|int
    {
        return $this->bits[$offset];
    }

    /**
     * @param  int  $offset
     * @param  int|bool  $value
     */
    public function offsetSet(mixed $offset, $value): void
    {
        $this->bits[$offset] = $value;
    }

    /** @param int $offset */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->bits[$offset]);
    }

    public function count(): int
    {
        return count($this->bits);
    }

    /** @return self<bool|int> */
    public function slice(int $offset, int $length): self
    {
        return new self(array_slice($this->bits, $offset, $length));
    }

    public function current(): bool|int
    {
        return current($this->bits);
    }

    public function next(): void
    {
        next($this->bits);
    }

    public function key(): int|null
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

    /**
     * @param  self<bool|int>  $bitArray
     * @return self<bool|int>
     */
    public function merge(self $bitArray): self
    {
        return new self(array_merge($this->bits, $bitArray->toArray()));
    }

    /** @return array<int, bool|int> */
    public function toArray(): array
    {
        return $this->bits;
    }
}
