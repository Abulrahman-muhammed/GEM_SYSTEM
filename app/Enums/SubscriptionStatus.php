<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'نشط',
            self::EXPIRED => 'منتهي',
            self::CANCELLED => 'ملغي',
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