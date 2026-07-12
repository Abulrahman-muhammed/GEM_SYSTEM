<?php

namespace App\Enums;

enum DiscountType: string
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';

    public function label(): string
    {
        return match ($this) {
            self::FIXED => 'قيمة ثابتة',
            self::PERCENTAGE => 'نسبة مئوية',
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
}