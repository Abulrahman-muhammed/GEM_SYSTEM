<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    /**
     * جلب قائمة المدفوعات مع البحث والـ pagination.
     * البحث بيشتغل على اسم العضو أو رقم هاتفه.
     */
    public function list(?string $search = null, int $perPage = 32): LengthAwarePaginator
    {
        return Payment::query()
            ->with(['subscription.member', 'subscription.plan'])
            ->when($search, function ($query, $search) {
                $query->whereHas('subscription.member', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest('payment_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * تسجيل دفعة جديدة على اشتراك (يستخدم من صفحة المدفوعات مباشرة).
     */
    public function create(Subscription $subscription, array $data): Payment
    {
        return DB::transaction(function () use ($subscription, $data) {
            return $subscription->payments()->create([
                'amount'       => $data['amount'],
                'method'       => $data['method'],
                'payment_date' => $data['payment_date'] ?? now(),
                'notes'        => $data['notes'] ?? null,
            ]);
        });
    }

    /**
     * حذف دفعة.
     */
    public function delete(Payment $payment): bool
    {
        return (bool) $payment->delete();
    }
}