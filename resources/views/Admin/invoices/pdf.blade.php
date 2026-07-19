<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $payment->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
        }
        .invoice-box {
            width: 320px;
            margin: 0 auto;
            border: 1px solid #999;
            border-radius: 6px;
            padding: 20px;
        }
        .gym-logo { font-size: 1.8rem; text-align: center; }
        .gym-name { font-weight: 700; letter-spacing: .1em; margin-top: 4px; text-align: center; }
        .divider { border-top: 1px dashed #999; margin: 12px 0; }
        .divider-light { border-top: 1px dashed #ccc; margin: 5px 0 8px; }
        .section-title { font-weight: 700; font-size: .82rem; }
        .row-line { display: flex; justify-content: space-between; font-size: .8rem; padding: 2px 0; }
        .row-total { font-weight: 700; border-top: 1px solid #333; margin-top: 4px; padding-top: 5px; }
        .text-center { text-align: center; }
        .thank-you { font-size: .9rem; font-weight: 600; }
    </style>
</head>
<body>

    @include('admin.invoices._content')

</body>
</html>