<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إيصال اشتراك #{{ $subscription->id }}</title>
    <style>
        body { font-family: 'Cairo', Arial, sans-serif; padding: 40px; color: #1f2937; }
        .receipt-box { max-width: 480px; margin: 0 auto; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; }
        .receipt-header { text-align: center; margin-bottom: 20px; }
        .receipt-header h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        td { padding: 8px 0; border-bottom: 1px dashed #e5e7eb; }
        td:last-child { text-align: left; font-weight: 600; }
        .total-row td { border-bottom: none; border-top: 2px solid #1f2937; font-size: 1.1rem; padding-top: 12px; }
        .print-btn { display: block; width: 100%; margin-top: 20px; padding: 10px; background: #0d6efd; color: #fff; border: none; border-radius: 8px; cursor: pointer; font-size: 1rem; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="receipt-header">
            <h2>إيصال اشتراك</h2>
            <small>رقم الإيصال: #{{ $subscription->id }} — {{ now()->format('Y-m-d H:i') }}</small>
        </div>

        <table>
            <tr><td>العضو</td><td>{{ $subscription->member->full_name }}</td></tr>
            <tr><td>رقم الهاتف</td><td>{{ $subscription->member->phone }}</td></tr>
            <tr><td>الخطة</td><td>{{ $subscription->plan->name }}</td></tr>
            <tr><td>العرض</td><td>{{ $subscription->offer?->name ?? '—' }}</td></tr>
            <tr><td>تاريخ البداية</td><td>{{ $subscription->start_date->format('Y-m-d') }}</td></tr>
            <tr><td>تاريخ النهاية</td><td>{{ $subscription->end_date->format('Y-m-d') }}</td></tr>
            <tr><td>السعر الأصلي</td><td>{{ number_format($subscription->original_price, 2) }} جنيه</td></tr>
            <tr><td>الخصم</td><td>{{ number_format($subscription->discount, 2) }} جنيه</td></tr>
            <tr class="total-row"><td>السعر النهائي</td><td>{{ number_format($subscription->final_price, 2) }} جنيه</td></tr>
            <tr><td>المدفوع</td><td>{{ number_format($subscription->paid_amount, 2) }} جنيه</td></tr>
            <tr><td>المتبقي</td><td>{{ number_format($subscription->remaining_amount, 2) }} جنيه</td></tr>
        </table>

        <button class="print-btn" onclick="window.print()">
            🖨️ طباعة
        </button>
    </div>
</body>
</html>