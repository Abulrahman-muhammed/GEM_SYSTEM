<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService)
    {
    }
 
    /**
     * عرض قائمة كل المدفوعات.
     */
    public function index(): View
    {
        $payments = $this->paymentService->list(
            search: request('search'),
            perPage: (int) request('per_page', 32),
        );
 
        return view('admin.payments.index', compact('payments'));
    }
 
    /**
     * عرض فورم تسجيل دفعة جديدة.
     * لو جاي من صفحة اشتراك معين، بيتحدد تلقائيًا في الفورم.
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
     * حفظ دفعة جديدة.
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
     * حذف دفعة.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $this->paymentService->delete($payment);
 
        return redirect()
            ->route('payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح.');
    }
}
