<?php

namespace App\Services;

use App\Models\Payment;

class InvoiceNumberService
{
    public function generate(): string
    {
        $today = now()->format('Ymd');

        $lastPayment = Payment::whereDate('created_at', today())
            ->latest('id')
            ->first();

        $sequence = $lastPayment
            ? ((int) substr($lastPayment->invoice_number, -6)) + 1
            : 1;

        return sprintf(
            'INV-%s-%06d',
            $today,
            $sequence
        );
    }
}