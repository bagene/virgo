<?php

namespace App\Concerns;

use InvalidArgumentException;
use ValueError;

trait EnumTool
{
    public static function fromString(string $name): self
    {
        $value = strtoupper($name);

        try {
            return self::{$value};
        } catch (ValueError) {
            throw new InvalidArgumentException("No enum case with name {$name}");
        }
    }

    public static function fromValue(mixed $value): self
    {
        foreach (self::cases() as $case) {
            if ($case->value === $value) {
                return $case;
            }
        }

        throw new InvalidArgumentException("No enum case with value {$value}");
    }
}
