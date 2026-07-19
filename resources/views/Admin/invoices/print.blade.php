<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $payment->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #f3f4f6;
            padding: 40px;
        }
        .invoice-box {
            width: 360px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 24px;
        }
        .gym-logo { font-size: 2rem; }
        .gym-name { font-weight: 700; letter-spacing: .1em; margin-top: 4px; }
        .divider { border-top: 1px dashed #9ca3af; margin: 14px 0; }
        .divider-light { border-top: 1px dashed #d1d5db; margin: 6px 0 10px; }
        .section-title { font-weight: 700; font-size: .85rem; }
        .row-line { display: flex; justify-content: space-between; font-size: .85rem; padding: 2px 0; }
        .row-total { font-weight: 700; border-top: 1px solid #1f2937; margin-top: 4px; padding-top: 6px; }
        .text-center { text-align: center; }
        .thank-you { font-size: .95rem; font-weight: 600; }

        .actions { max-width: 360px; margin: 20px auto 0; display: flex; gap: 10px; }
        .actions .btn {
            flex: 1; padding: 10px; border-radius: 8px; border: none;
            cursor: pointer; font-size: .9rem; text-decoration: none; text-align: center; display: inline-block;
            font-family: 'Cairo', Arial, sans-serif;
        }
        .btn-download { background: #0d6efd; color: #fff; }
        .btn-print { background: #f1f3f8; color: #1f2937; }

        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { border: none; }
            .actions { display: none; }
        }
    </style>
</head>
<body>

    @include('admin.invoices._content')

    <div class="actions">
        <a href="{{ route('payments.invoice.download', $payment) }}" class="btn btn-download">⬇ Download PDF</a>
        <button type="button" class="btn btn-print" onclick="window.print()">🖨 Print</button>
    </div>

</body>
</html>