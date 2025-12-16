<?php

namespace App\Concerns;

trait EnumTool
{
    public function is(self $enum): bool
    {
        return $this === $enum;
    }

    public function label(): string
    {
        return ucfirst(strtolower(str_replace('_', ' ', $this->name)));
    }

    /**
     * @return string[]
     */
    public static function labels(): array
    {
        return array_map(
            fn (self $case) => $case->label(),
            self::cases()
        );
    }

    public static function tryFrom(mixed $value): ?self
    {
        try {
            return self::from($value);
        } catch (\ValueError) {
            return null;
        }
    }

    public static function fromLabel(string $label): self
    {
        foreach (self::cases() as $case) {
            if ($case->label() === ucfirst(strtolower(str_replace('_', ' ', $label)))) {
                return $case;
            }
        }

        throw new \ValueError("No enum case found for label: {$label}");
    }
}
