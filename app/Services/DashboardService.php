<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;

class DashboardService
{
    /**
     * كل بيانات الداشبورد في مكان واحد.
     */
    public function getData(): array
    {
        return [
            'kpis'                  => $this->kpis(),
            'attendanceLast7Days'   => $this->attendanceLast7Days(),
            'subscriptionsBreakdown' => $this->subscriptionsBreakdown(),
            'recentAttendance'      => $this->recentAttendance(),
            'expiringSoon'          => $this->expiringSoon(),
            'revenueStats'          => $this->revenueStats(),
            'recentPayments'        => $this->recentPayments(),
            'alerts'                => $this->alerts(),
        ];
    }

    /* ══════════════════════════ الصف الأول: KPIs ══════════════════════════ */

    protected function kpis(): array
    {
        return [
            'total_members'          => Member::count(),
            'today_attendance'       => Attendance::whereDate('date', today())->count(),
            'today_revenue'          => (float) Payment::whereDate('payment_date', today())->sum('amount'),
            'expiring_subscriptions' => Subscription::where('status', 'active')
                ->whereBetween('end_date', [today(), today()->addDays(7)])
                ->count(),
        ];
    }

    /* ══════════════════════════ الصف الثاني: الشارتس ══════════════════════════ */

    /**
     * عدد الحضور لكل يوم في آخر 7 أيام (لعمل Bar/Line chart).
     */
    protected function attendanceLast7Days(): array
    {
        $labels = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = today()->subDays($i);

            $labels[] = $day->translatedFormat('D'); // اسم اليوم مختصر بالعربي لو الـ locale متظبطة
            $counts[] = Attendance::whereDate('date', $day)->count();
        }

        return ['labels' => $labels, 'data' => $counts];
    }

    /**
     * توزيع الاشتراكات حسب الحالة (لعمل Pie/Donut chart).
     */
    protected function subscriptionsBreakdown(): array
    {
        $total = Subscription::count();

        $counts = [
            'active'    => Subscription::where('status', 'active')->count(),
            'expired'   => Subscription::where('status', 'expired')->count(),
            'frozen'    => Subscription::where('status', 'frozen')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
        ];

        $percentages = [];
        foreach ($counts as $key => $count) {
            $percentages[$key] = $total > 0 ? round(($count / $total) * 100) : 0;
        }

        return ['counts' => $counts, 'percentages' => $percentages, 'total' => $total];
    }

    /* ══════════════════════════ الصف الثالث: جداول ══════════════════════════ */

    protected function recentAttendance(int $limit = 6)
    {
        return Attendance::with('member')
            ->whereDate('date', today())
            ->latest('check_in')
            ->limit($limit)
            ->get();
    }

    protected function expiringSoon(int $limit = 6)
    {
        return Subscription::with('member')
            ->where('status', 'active')
            ->whereBetween('end_date', [today(), today()->addDays(7)])
            ->orderBy('end_date')
            ->limit($limit)
            ->get();
    }

    /* ══════════════════════════ الصف الرابع: الإيرادات ══════════════════════════ */

    protected function revenueStats(): array
    {
        return [
            'today' => (float) Payment::whereDate('payment_date', today())->sum('amount'),
            'week'  => (float) Payment::whereBetween('payment_date', [
                today()->startOfWeek(), today()->endOfWeek(),
            ])->sum('amount'),
            'month' => (float) Payment::whereMonth('payment_date', today()->month)
                ->whereYear('payment_date', today()->year)
                ->sum('amount'),
        ];
    }

    /* ══════════════════════════ الصف الخامس: آخر المدفوعات ══════════════════════════ */

    protected function recentPayments(int $limit = 6)
    {
        return Payment::with('subscription.member')
            ->latest('payment_date')
            ->limit($limit)
            ->get();
    }

    /* ══════════════════════════ التنبيهات ══════════════════════════ */

    protected function alerts(): array
    {
        $alerts = [];

        $expiringCount = Subscription::where('status', 'active')
            ->whereBetween('end_date', [today(), today()->addDays(3)])
            ->count();

        if ($expiringCount > 0) {
            $alerts[] = [
                'type'    => 'warning',
                'icon'    => 'fe-alert-triangle',
                'message' => "{$expiringCount} اشتراكات هتنتهي خلال 3 أيام",
            ];
        }

        $birthdaysToday = Member::whereNotNull('birth_date')
            ->whereMonth('birth_date', today()->month)
            ->whereDay('birth_date', today()->day)
            ->count();

        if ($birthdaysToday > 0) {
            $alerts[] = [
                'type'    => 'info',
                'icon'    => 'fe-gift',
                'message' => $birthdaysToday === 1
                    ? 'عضو عيد ميلاده النهاردة 🎂'
                    : "{$birthdaysToday} أعضاء عيد ميلادهم النهاردة 🎂",
            ];
        }

        return $alerts;
    }
}