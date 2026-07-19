<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\InvoiceNumberService;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if ($payment->invoice_number) {
            return;
        }

        $payment->invoice_number = app(InvoiceNumberService::class)->generate();
    }
}
