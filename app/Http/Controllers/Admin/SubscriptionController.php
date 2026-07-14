<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Member;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function __construct(protected SubscriptionService $subscriptionService)
    {
    }

    /**
     * عرض قائمة الاشتراكات.
     */
    public function index(): View
    {
        $subscriptions = $this->subscriptionService->list(
            search: request('search'),
            perPage: (int) request('per_page', 32),
        );

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * عرض فورم إضافة اشتراك جديد.
     */
    public function create(): View
    {
        $members = Member::orderBy('full_name')->get(['id', 'full_name', 'phone']);
        $plans   = Plan::active()->orderBy('name')->get(['id', 'name', 'price', 'duration_days']);
        $offers  = Offer::active()->orderBy('name')->get(['id', 'name', 'discount_type', 'discount_value']);

        return view('admin.subscriptions.create', compact('members', 'plans', 'offers'));
    }

    /**
     * حفظ اشتراك جديد.
     */
    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $this->subscriptionService->createSubscription($request->validated());

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم إنشاء الاشتراك بنجاح.');
    }

    /**
     * عرض تفاصيل اشتراك (تستخدم برضو لطباعة الإيصال).
     */
    public function show(Subscription $subscription): View
    {
        $subscription->load(['member', 'plan', 'offer', 'payments']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * حذف اشتراك.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->delete($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم حذف الاشتراك بنجاح.');
    }

    /* ══════════════════════════ Actions إضافية ══════════════════════════ */

    /**
     * تجديد الاشتراك.
     */
    public function renew(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->renewSubscription($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم تجديد الاشتراك بنجاح.');
    }

    /**
     * تجميد الاشتراك.
     */
    public function freeze(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->freezeSubscription($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم تجميد الاشتراك.');
    }

    /**
     * إلغاء تجميد الاشتراك.
     */
    public function unfreeze(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->unfreezeSubscription($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم إلغاء تجميد الاشتراك.');
    }

    /**
     * إلغاء الاشتراك نهائيًا.
     */
    public function cancel(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->cancelSubscription($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم إلغاء الاشتراك.');
    }

    /**
     * طباعة إيصال الاشتراك.
     */
    public function printReceipt(Subscription $subscription): View
    {
        $subscription->load(['member', 'plan', 'offer', 'payments']);

        return view('admin.subscriptions.receipt', compact('subscription'));
    }
}