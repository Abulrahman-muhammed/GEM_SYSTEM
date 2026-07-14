<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الأعضاء</title>
    <style>
        body { font-family: 'Cairo', Arial, sans-serif; font-size: 12px; padding: 20px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #6b7280; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: right; }
        th { background: #f8f9fc; }
    </style>
</head>
<body>
    <h2>تقرير الأعضاء</h2>
    <div class="subtitle">تاريخ التصدير: {{ now()->format('Y-m-d h:i A') }} — إجمالي: {{ $members->count() }} عضو</div>

    <table>
        <thead>
            <tr>
                <th>رقم العضوية</th>
                <th>الاسم</th>
                <th>الهاتف</th>
                <th>النوع</th>
                <th>الحالة</th>
                <th>تاريخ التسجيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->full_name }}</td>
                    <td>{{ $member->phone }}</td>
                    <td>{{ $member->gender?->label() }}</td>
                    <td>{{ $member->status ? 'نشط' : 'غير نشط' }}</td>
                    <td>{{ $member->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>