<?php

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Models\Member;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * جلب قائمة الاشتراكات مع البحث والـ pagination.
     */
    public function list(?string $search = null, int $perPage = 32): LengthAwarePaginator
    {
        return Subscription::query()
            ->with(['member', 'plan', 'offer'])
            ->when($search, function ($query, $search) {
                $query->whereHas('member', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * حساب قيمة الخصم بناءً على السعر الأصلي والعرض المختار.
     * يرجع مصفوفة فيها [discount, final_price].
     */
    public function calculateDiscount(float $originalPrice, ?Offer $offer): array
    {
        if (! $offer || ! $offer->status) {
            return [
                'discount'    => 0,
                'final_price' => round($originalPrice, 2),
            ];
        }

        $discount = $offer->discount_type->value === 'percentage'
            ? $originalPrice * ((float) $offer->discount_value / 100)
            : (float) $offer->discount_value;

        // الخصم متعديش السعر الأصلي أبدًا.
        $discount = min($discount, $originalPrice);

        return [
            'discount'    => round($discount, 2),
            'final_price' => round($originalPrice - $discount, 2),
        ];
    }

    /**
     * حساب تاريخ نهاية الاشتراك بناءً على مدة الخطة بالأيام.
     */
    public function calculateEndDate(Carbon|string $startDate, Plan $plan): Carbon
    {
        $start = $startDate instanceof Carbon ? $startDate : Carbon::parse($startDate);

        return $start->copy()->addDays($plan->duration_days);
    }

    /**
     * إنشاء اشتراك جديد + أول عملية دفع (لو موجودة).
     *
     * $data المتوقع:
     * member_id, plan_id, offer_id?, start_date,
     * payment_method?, paid_amount?
     */
    public function createSubscription(array $data): Subscription
    {
        return DB::transaction(function () use ($data) {
            $plan  = Plan::findOrFail($data['plan_id']);
            $offer = ! empty($data['offer_id']) ? Offer::find($data['offer_id']) : null;

            $originalPrice = (float) $plan->price;
            $calc          = $this->calculateDiscount($originalPrice, $offer);
            $endDate       = $this->calculateEndDate($data['start_date'], $plan);

            $subscription = Subscription::create([
                'member_id'      => $data['member_id'],
                'plan_id'        => $plan->id,
                'offer_id'       => $offer?->id,
                'start_date'     => $data['start_date'],
                'end_date'       => $endDate,
                'original_price' => $originalPrice,
                'discount'       => $calc['discount'],
                'final_price'    => $calc['final_price'],
                'status'         => SubscriptionStatus::ACTIVE->value,
            ]);

            if (! empty($data['paid_amount']) && (float) $data['paid_amount'] > 0) {
                $this->createPayment($subscription, [
                    'amount' => $data['paid_amount'],
                    'method' => $data['payment_method'] ?? 'cash',
                    'payment_date' => $data['start_date'],
                ]);
            }

            return $subscription->fresh(['member', 'plan', 'offer', 'payments']);
        });
    }

    /**
     * تسجيل عملية دفع جديدة على اشتراك.
     */
    public function createPayment(Subscription $subscription, array $data): Subscription
    {
        return DB::transaction(function () use ($subscription, $data) {
            $subscription->payments()->create([
                'amount'  => $data['amount'],
                'method'  => $data['method'],
                'payment_date' => $data['paid_at'] ?? now(),
                'notes'   => $data['notes'] ?? null,
            ]);

            return $subscription->fresh(['payments']);
        });
    }

    /**
     * تجديد الاشتراك: بيعمل اشتراك امتداد لنفس الخطة يبدأ من تاريخ الانتهاء
     * الحالي (أو اليوم لو الاشتراك خلص فعلاً)، ويقفل القديم كـ "منتهي".
     */
    public function renewSubscription(Subscription $subscription, ?string $startDate = null): Subscription
    {
        return DB::transaction(function () use ($subscription, $startDate) {
            $newStart = $startDate
                ? Carbon::parse($startDate)
                : ($subscription->end_date->isFuture() ? $subscription->end_date : now());

            $renewed = $this->createSubscription([
                'member_id'  => $subscription->member_id,
                'plan_id'    => $subscription->plan_id,
                'offer_id'   => $subscription->offer_id,
                'start_date' => $newStart->format('Y-m-d'),
            ]);

            if ($subscription->status !== SubscriptionStatus::EXPIRED) {
                $subscription->update(['status' => SubscriptionStatus::EXPIRED->value]);
            }

            return $renewed;
        });
    }

    /**
     * تجميد الاشتراك.
     */
    public function freezeSubscription(Subscription $subscription): Subscription
    {
        $subscription->update(['status' => SubscriptionStatus::FROZEN->value]);

        return $subscription->fresh();
    }

    /**
     * إلغاء تجميد الاشتراك وإرجاعه نشط.
     */
    public function unfreezeSubscription(Subscription $subscription): Subscription
    {
        $subscription->update(['status' => SubscriptionStatus::ACTIVE->value]);

        return $subscription->fresh();
    }

    /**
     * إلغاء الاشتراك نهائيًا.
     */
    public function cancelSubscription(Subscription $subscription): Subscription
    {
        $subscription->update(['status' => SubscriptionStatus::CANCELLED->value]);

        return $subscription->fresh();
    }

    /**
     * حذف اشتراك.
     */
    public function delete(Subscription $subscription): bool
    {
        return (bool) $subscription->delete();
    }
}