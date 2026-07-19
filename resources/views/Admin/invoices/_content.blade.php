@php
    $subscription = $payment->subscription;
    $member       = $subscription->member;
    $plan         = $subscription->plan;
@endphp

<div class="invoice-box">
    <div class="invoice-section text-center">
        <div class="gym-logo">
            <img src="{{ Storage::url($settings->logo) }}" alt="Current Logo" style="max-height: 50px; border-radius: 8px;">
        </div>
        <div class="gym-name">{{ $settings->gym_name }}</div>
    </div>

    <div class="divider"></div>

    <div class="invoice-section">
        <div class="row-line"><span>Invoice</span><span>: {{ $payment->invoice_number }}</span></div>
        <div class="row-line"><span>Date</span><span>: {{ $payment->payment_date->format('d/m/Y') }}</span></div>
    </div>

    <div class="divider"></div>

    <div class="invoice-section">
        <div class="section-title">Member Information</div>
        <div class="divider-light"></div>
        <div class="row-line"><span>Name</span><span>: {{ $member->full_name }}</span></div>
        <div class="row-line"><span>Phone</span><span>: {{ $member->phone }}</span></div>
    </div>

    <div class="divider"></div>

    <div class="invoice-section">
        <div class="section-title">Subscription</div>
        <div class="divider-light"></div>
        <div class="row-line"><span>Plan</span><span>: {{ $plan->name }}</span></div>
        <div class="row-line"><span>Start</span><span>: {{ $subscription->start_date->format('d/m/Y') }}</span></div>
        <div class="row-line"><span>End</span><span>: {{ $subscription->end_date->format('d/m/Y') }}</span></div>
        <div class="row-line"><span>Duration</span><span>: {{ $plan->duration_days }} Days</span></div>
    </div>

    <div class="divider"></div>

    <div class="invoice-section">
        <div class="section-title">Payment Details</div>
        <div class="divider-light"></div>
        <div class="row-line"><span>Original Price</span><span>{{ number_format($subscription->original_price, 0) }} EGP</span></div>
        <div class="row-line"><span>Discount</span><span>{{ number_format($subscription->discount, 0) }} EGP</span></div>
        <div class="row-line row-total"><span>Total</span><span>{{ number_format($subscription->final_price, 0) }} EGP</span></div>
        <div class="row-line"><span>Paid</span><span>{{ number_format($subscription->paid_amount, 0) }} EGP</span></div>
        <div class="row-line"><span>Remaining</span><span>{{ number_format($subscription->remaining_amount, 0) }} EGP</span></div>
    </div>

    <div class="divider"></div>

    <div class="invoice-section">
        <div class="row-line"><span>Payment Method</span><span>: {{ $payment->method->label() }}</span></div>
    </div>

    <div class="divider"></div>

    @if (isset($qrCode))
        <div class="invoice-section text-center">
            {!! $qrCode !!}
        </div>
        <div class="divider"></div>
    @endif

    <div class="invoice-section text-center thank-you">
        Thank you ❤️
    </div>
</div>