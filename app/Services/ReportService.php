<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Offer;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /* ══════════════════════════ 1) تقرير الأعضاء ══════════════════════════ */

    public function membersReport(?string $search = null): array
    {
        $query = Member::query()
            ->when($search, function ($q, $search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });

        return [
            'total_members'    => Member::count(),
            'active_members'   => Member::where('status', true)->count(),
            'inactive_members' => Member::where('status', false)->count(),
            'new_this_month'   => Member::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'members' => (clone $query)->latest()->paginate(20)->withQueryString(),
        ];
    }

    /**
     * نفس فلتر membersReport بس من غير pagination — يستخدم في الـ Export.
     */
    public function membersForExport(?string $search = null)
    {
        return Member::query()
            ->when($search, function ($q, $search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->get();
    }

    /* ══════════════════════════ 2) تقرير الحضور ══════════════════════════ */

    public function attendanceReport(?string $from = null, ?string $to = null): array
    {
        $from = $from ? Carbon::parse($from)->startOfDay() : now()->subDays(30)->startOfDay();
        $to   = $to ? Carbon::parse($to)->endOfDay() : now()->endOfDay();

        $topMembers = Member::withCount(['attendances as visits_count' => function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to]);
            }])
            ->orderByDesc('visits_count')
            ->limit(5)
            ->get();

        $leastMembers = Member::withCount(['attendances as visits_count' => function ($q) use ($from, $to) {
                $q->whereBetween('date', [$from, $to]);
            }])
            ->orderBy('visits_count')
            ->limit(5)
            ->get();

        return [
            'from'             => $from->format('Y-m-d'),
            'to'               => $to->format('Y-m-d'),
            'today_count'      => Attendance::whereDate('date', today())->count(),
            'week_count'       => Attendance::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'top_members'      => $topMembers,
            'least_members'    => $leastMembers,
            'attendances'      => Attendance::with('member')
                ->whereBetween('date', [$from, $to])
                ->latest('date')
                ->latest('check_in')
                ->paginate(20)
                ->withQueryString(),
        ];
    }

    /* ══════════════════════════ 3) تقرير العروض ══════════════════════════ */

    public function offersReport(): array
    {
        $offers = Offer::withCount('subscriptions')
            ->withSum('subscriptions as revenue', 'final_price')
            ->orderByDesc('subscriptions_count')
            ->get();

        $bestSelling = $offers->sortByDesc('subscriptions_count')->first();

        return [
            'offers'       => $offers,
            'best_selling' => $bestSelling && $bestSelling->subscriptions_count > 0 ? $bestSelling : null,
        ];
    }

    /* ══════════════════════════ 4) تقرير الإيرادات ══════════════════════════ */

    public function revenueReport(string $period = 'today', ?string $from = null, ?string $to = null): array
    {
        [$start, $end] = $this->resolveRange($period, $from, $to);

        $totalRevenue = (float) Payment::whereBetween('payment_date', [$start, $end])->sum('amount');

        $subscriptionsCount = Subscription::whereBetween('created_at', [$start, $end])->count();

        $discountsTotal = (float) Subscription::whereBetween('created_at', [$start, $end])->sum('discount');

        // اشتراكات نشطة عليها متبقي (متأخرين في الدفع)، بغض النظر عن الفترة.
        $lateCount = Subscription::where('status', 'active')
            ->whereRaw('final_price > (select coalesce(sum(amount), 0) from payments where payments.subscription_id = subscriptions.id)')
            ->count();

        return [
            'period'              => $period,
            'from'                => $start->format('Y-m-d'),
            'to'                  => $end->format('Y-m-d'),
            'total_revenue'       => $totalRevenue,
            'subscriptions_count' => $subscriptionsCount,
            'late_count'          => $lateCount,
            'discounts_total'     => $discountsTotal,
        ];
    }

    /**
     * تحويل الفترة المختارة (today/week/month/year/custom) لتاريخ بداية ونهاية.
     */
    protected function resolveRange(string $period, ?string $from, ?string $to): array
    {
        return match ($period) {
            'week'   => [now()->startOfWeek(), now()->endOfWeek()],
            'month'  => [now()->startOfMonth(), now()->endOfMonth()],
            'year'   => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [
                $from ? Carbon::parse($from)->startOfDay() : now()->startOfMonth(),
                $to ? Carbon::parse($to)->endOfDay() : now()->endOfDay(),
            ],
            default  => [now()->startOfDay(), now()->endOfDay()], // today
        };
    }
}