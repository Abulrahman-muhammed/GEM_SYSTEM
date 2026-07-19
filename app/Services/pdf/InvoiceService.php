<?php

namespace App\Services\Pdf;

use App\Models\Payment;
use App\Settings\GeneralSettings;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceService
{
    public function __construct(
        protected GeneralSettings $settings
    ) {}

    protected function data(Payment $payment): array
    {
        $payment->loadMissing([
            'subscription.member',
            'subscription.plan',
            'subscription.offer',
        ]);

        return [
            'payment'  => $payment,
            'settings' => $this->settings,
        ];
    }

    public function download(Payment $payment)
    {
        return Pdf::loadView(
            'admin.invoices.pdf',
            $this->data($payment)
        )
        ->setPaper('A4')
        ->download("Invoice-{$payment->invoice_number}.pdf");
    }
}