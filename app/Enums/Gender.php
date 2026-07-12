<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'ذكر',
            self::FEMALE => 'أنثى',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn ($case) => [
                    'value' => $case->value,
                    'label' => $case->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
    public function emoji(): string
    {
        return match($this) {
            self::MALE   => '👨',
            self::FEMALE => '👩',
        };
    }
}