<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    /** @use HasFactory<\Database\Factories\PlanFactory> */
    use HasFactory;
  

    protected $fillable = [
    'name',
    'price',
    'duration_days',
    'description',
    'status',
];
    protected $casts = [
        'price'         => 'decimal:2',
        'duration_days' => 'integer',
        'status'        => 'boolean',
    ];
public function subscriptions()
{
    return $this->hasMany(Subscription::class);
}

/**
 * Scope active plans.
 */
public function scopeActive($query)
{
    return $query->where('status', true);
}

}
