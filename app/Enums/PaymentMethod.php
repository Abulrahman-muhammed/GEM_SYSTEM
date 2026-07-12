<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case VISA = 'visa';
    case WALLET = 'wallet';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'كاش',
            self::VISA => 'فيزا',
            self::WALLET => 'محفظة إلكترونية',
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