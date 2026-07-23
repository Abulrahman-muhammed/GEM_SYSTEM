<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * Add new payment to subscription (used from subscription page directly).
     */
    public function create(Subscription $subscription, array $data): Payment
    {
        return DB::transaction(function () use ($subscription, $data) {
            return $subscription->payments()->create([
                'amount'       => $data['amount'],
                'method'       => $data['method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'notes'        => $data['notes'] ?? null,
            ]);
        });
    }
}