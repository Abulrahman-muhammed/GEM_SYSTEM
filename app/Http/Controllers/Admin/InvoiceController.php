<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\Pdf\InvoiceService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceController extends Controller
{
    public function print(Payment $payment)
    {
        return view('admin.invoices.print', [
            'payment' => $payment->load([
                'subscription.member',
                'subscription.plan',
                'subscription.offer',
            ]),
            'qrCode'=> QrCode::size(110)->generate($payment->invoice_number),
        ]);
    }

    public function download(Payment $payment, InvoiceService $service)
    {
        return $service->download($payment);
    }
}