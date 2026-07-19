<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentMethod;
class Payment extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentFactory> */
    use HasFactory;
    protected $fillable = [
        'subscription_id',
        'invoice_number',
        'amount',
        'method',
        'payment_date',
        'notes',
    ];
 
    protected $casts = [
        'amount'  => 'decimal:2',
        'method'  => PaymentMethod::class,
        'payment_date' => 'date',
    ];
 
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
