<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\DiscountType;

class Offer extends Model
{
    /** @use HasFactory<\Database\Factories\OfferFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'description',
        'status',
    ];
 
    /**
     * تحويل الأنواع تلقائيًا.
     */
    protected $casts = [
        'discount_type'  => DiscountType::class,
        'discount_value' => 'decimal:2',
        'start_date'     => 'date',
        'end_date'       => 'date',
        'status'         => 'boolean',
    ];
 
    /**
     * Scope: العروض النشطة فقط (status = true).
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
 
    /**
     * Scope: العروض السارية حاليًا (نشطة + داخل مدى التاريخ).
     */
    public function scopeCurrentlyValid($query)
    {
        return $query->where('status', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
 
    /**
     * هل العرض منتهي الصلاحية؟
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date->isPast();
    }
    /**
     * حساب القيمة النهائية للخصم على سعر معين.
     */
    public function calculateDiscount($originalPrice): float
    {
        return match($this->discount_type) {
            DiscountType::PERCENTAGE => $originalPrice * ($this->discount_value / 100),
            DiscountType::FIXED      => (float) $this->discount_value,
            default => 0.00,
        };
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
