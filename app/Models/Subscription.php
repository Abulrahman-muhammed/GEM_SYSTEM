<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;
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
}
