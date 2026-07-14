<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SubscriptionStatus;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;
    protected $fillable = [
        'member_id',
        'plan_id',
        'offer_id',
        'start_date',
        'end_date',
        'original_price',
        'discount',
        'final_price',
        'status',
    ];
 
    /**
     * تحويل الأنواع تلقائيًا.
     */
    protected $casts = [
        'start_date'      => 'date',
        'end_date'        => 'date',
        'original_price'  => 'decimal:2',
        'discount'        => 'decimal:2',
        'final_price'     => 'decimal:2',
        'status'          => SubscriptionStatus::class,
    ];
 
    /* ══════════════════════════ العلاقات ══════════════════════════ */
 
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
 
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
 
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
 
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
 
    /* ══════════════════════════ Accessors ══════════════════════════ */
 
    /**
     * إجمالي ما تم دفعه فعليًا.
     */
    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }
 
    /**
     * المبلغ المتبقي = السعر النهائي - المدفوع.
     */
    public function getRemainingAmountAttribute(): float
    {
        return round((float) $this->final_price - $this->paid_amount, 2);
    }
 
    /**
     * عدد الأيام المتبقية على انتهاء الاشتراك (سالب لو منتهي).
     */
    public function getRemainingDaysAttribute(): int
    {
        return (int) now()->startOfDay()->diffInDays($this->end_date, false);
    }
}
