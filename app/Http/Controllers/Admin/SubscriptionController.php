<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubscriptionRequest;
use App\Models\Member;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Enums\SubscriptionStatus;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService,
        protected SearchService $searchService
    ) {
    }

    /**
     * List subscriptions
     */
    public function index(Request $request)
    {
        $query = Subscription::query()
            ->with(['member','plan','offer']);
        $search = $request->get('search');
        $this->searchService->apply(
            $query,
            $search,
            [
                'member.full_name',
                'member.phone',
            ]
        );
        $subscriptions = $query
            ->latest()
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * show create subscription form
     */
    public function create(Request $request): View
    {
        return view(
            'admin.subscriptions.create',
            $this->subscriptionService->createData(
                $request->integer('member')
            )
        );
    }
    /**
     * store new subscription
     */
    public function store(StoreSubscriptionRequest $request): RedirectResponse
    {
        $this->subscriptionService->createSubscription($request->validated());

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم إنشاء الاشتراك بنجاح.');
    }

    /**
     * show subscription details (used also for printing )
     */
    public function show(Subscription $subscription): View
    {
        $subscription->load(['member', 'plan', 'offer', 'payments']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * delete subscription.
     */
    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم حذف الاشتراك بنجاح.');
    }

    /**
     * renew subscription.
     */
    public function renew(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->renewSubscription($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم تجديد الاشتراك بنجاح.');
    }

    /**
     * freeze subscription
     */
    public function freeze(Request $request, Subscription $subscription)
    {
        $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $this->subscriptionService->freezeSubscription(
            $subscription,
            $request->only('reason')
        );

        return back()->with('success', 'تم تجميد الاشتراك بنجاح.');
    }

    /**
     * unfreeze subscription.
     */
    public function unfreeze(Subscription $subscription): RedirectResponse
    {
        $this->subscriptionService->unfreezeSubscription($subscription);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم إلغاء تجميد الاشتراك.');
    }

    /**
     * cancel subscription.
     */
    public function cancel(Subscription $subscription): RedirectResponse
    {
        $subscription->update(['status' => SubscriptionStatus::CANCELLED->value]);

        return redirect()
            ->route('subscriptions.index')
            ->with('success', 'تم إلغاء الاشتراك.');
    }


}