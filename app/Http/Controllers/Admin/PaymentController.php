<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentService;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService, protected SearchService $searchService)
    {}
 
    /**
     * Display payments.
     */
    public function index(Request $request)
    {
        $query = Payment::query()
            ->with(['subscription.member', 'subscription.plan']);

        $this->searchService->apply(
            $query,
            $request->search,
            [
                'subscription.member.full_name',
                'subscription.member.phone',
            ]
        );

        $payments = $query
            ->latest('payment_date')
            ->paginate($request->integer('per_page', 32))
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }
 
    /**
     * Add new payment.
     */
    public function create(): View
    {
        $subscriptions = Subscription::with('member', 'plan')
            ->whereIn('status', ['active', 'frozen'])
            ->latest()
            ->get();
 
        $selectedSubscriptionId = request('subscription_id');
 
        return view('admin.payments.create', compact('subscriptions', 'selectedSubscriptionId'));
    }
 
    /**
     * Store new payment.
     */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $subscription = Subscription::findOrFail($request->validated('subscription_id'));
        $this->paymentService->create($subscription, $request->validated());
 
        return redirect()
            ->route('payments.index')
            ->with('success', 'تم تسجيل الدفعة بنجاح.');
    }
 
    /**
     * Delete payment.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();
        return redirect()
            ->route('payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح.');
    }
}
