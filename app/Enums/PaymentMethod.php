<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH          = 'cash';
    case VODAFONE_CASH  = 'vodafone_cash';
    case INSTAPAY      = 'instapay';
    case CARD          = 'card';
 
    /**
     * الاسم المعروض بالعربي.
     */
    public function label(): string
    {
        return match ($this) {
            self::CASH          => 'كاش',
            self::VODAFONE_CASH => 'فودافون كاش',
            self::INSTAPAY      => 'إنستاباي',
            self::CARD          => 'بطاقة',
        };
    }
 
    /**
     * أيقونة Feather مناسبة لكل طريقة دفع.
     */
    public function icon(): string
    {
        return match ($this) {
            self::CASH          => 'fe-dollar-sign',
            self::VODAFONE_CASH => 'fe-smartphone',
            self::INSTAPAY      => 'fe-send',
            self::CARD          => 'fe-credit-card',
        };
    }
}