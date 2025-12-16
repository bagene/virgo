<?php

namespace App\Concerns;

trait EnumTool
{
    public function is(self $enum): bool
    {
        return $this === $enum;
    }

    public static function tryFrom(mixed $value): ?self
    {
        try {
            return self::from($value);
        } catch (\ValueError) {
            return null;
        }
    }
}
