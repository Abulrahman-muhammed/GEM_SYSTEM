@extends('admin.layouts.master')
@section('title', 'اشتراك جديد')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">إنشاء اشتراك جديد</h2>

    <form action="{{ route('subscriptions.store') }}" method="POST" id="subscription-form">
        @csrf

        <div class="row">
            <div class="col-lg-8">

                {{-- بيانات الاشتراك --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <i class="fe fe-repeat me-2"></i>
                        <strong>بيانات الاشتراك</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-grid-2">
                            {{-- العضو --}}
                            <div>
                                <label class="form-label font-weight-bold">العضو <span class="text-danger">*</span></label>
                                    <select name="member_id" class="form-control">
                                        @foreach($members as $item)
                                            <option
                                                value="{{ $item->id }}"
                                                @selected(old('member_id', $member?->id) == $item->id)
                                            >
                                                {{ $item->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                @error('member_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- الخطة --}}
                            <div>
                                <label class="form-label font-weight-bold">الخطة <span class="text-danger">*</span></label>
                                <select name="plan_id" id="plan_id"
                                        class="form-control @error('plan_id') is-invalid @enderror">
                                    <option value="">اختر الخطة...</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            data-price="{{ $plan->price }}"
                                            data-duration="{{ $plan->duration_days }}"
                                            {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} ({{ $plan->duration_days }} يوم - {{ number_format($plan->price, 2) }} جنيه)
                                        </option>
                                    @endforeach
                                </select>
                                @error('plan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- العرض --}}
                            <div>
                                <label class="form-label font-weight-bold">العرض <small class="text-muted">(اختياري)</small></label>
                                <select name="offer_id" id="offer_id" class="form-control @error('offer_id') is-invalid @enderror">
                                    <option value="">بدون عرض</option>
                                    @foreach ($offers as $offer)
                                        <option value="{{ $offer->id }}"
                                            data-type="{{ $offer->discount_type->value }}"
                                            data-value="{{ $offer->discount_value }}"
                                            {{ old('offer_id') == $offer->id ? 'selected' : '' }}>
                                            {{ $offer->name }}
                                            ({{ $offer->discount_type->value === 'percentage' ? $offer->discount_value.'%' : number_format($offer->discount_value, 2).' جنيه' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('offer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- تاريخ البداية --}}
                            <div>
                                <label class="form-label font-weight-bold">تاريخ البداية <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', now()->format('Y-m-d')) }}">
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- تاريخ النهاية (يتحسب تلقائيًا) --}}
                            <div>
                                <label class="form-label font-weight-bold">تاريخ النهاية</label>
                                <input type="text" id="end_date_display" class="form-control bg-light" readonly
                                       placeholder="بيتحسب تلقائيًا حسب الخطة">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- الدفع --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <i class="fe fe-credit-card me-2"></i>
                        <strong>الدفع</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label font-weight-bold d-block">طريقة الدفع</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach(\App\Enums\PaymentMethod::cases() as $method)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                               id="method_{{ $method->value }}" value="{{ $method->value }}"
                                               {{ old('payment_method', 'cash') === $method->value ? 'checked' : '' }}>
                                        <label class="form-check-label" for="method_{{ $method->value }}">
                                            <i class="fe {{ $method->icon() }} me-1"></i> {{ $method->label() }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-grid-2 mb-0">
                            <div>
                                <label class="form-label font-weight-bold">المبلغ المدفوع</label>
                                <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount"
                                       class="form-control @error('paid_amount') is-invalid @enderror"
                                       value="{{ old('paid_amount', 0) }}">
                                @error('paid_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label font-weight-bold">المبلغ المتبقي</label>
                                <input type="text" id="remaining_amount_display" class="form-control bg-light" readonly value="0.00">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ملخص السعر --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4 summary-card">
                    <div class="card-header bg-white">
                        <i class="fe fe-file-text me-2"></i>
                        <strong>ملخص السعر</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">السعر الأصلي</span>
                            <strong id="original_price_display">0.00 جنيه</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">قيمة الخصم</span>
                            <strong id="discount_display" class="text-danger">- 0.00 جنيه</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">السعر النهائي</span>
                            <strong id="final_price_display" class="text-primary fs-5">0.00 جنيه</strong>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fe fe-save me-1"></i> حفظ الاشتراك
                </button>
                <a href="{{ route('subscriptions.index') }}" class="btn btn-light btn-block mt-2">
                    <i class="fe fe-x me-1"></i> إلغاء
                </a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 1.5rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 767px) {
        .form-grid-2 { grid-template-columns: 1fr; }
    }

    .summary-card { position: sticky; top: 1rem; }

    .card { border-radius: .9rem; overflow: hidden; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    var planSelect   = document.getElementById('plan_id');
    var offerSelect  = document.getElementById('offer_id');
    var startDate    = document.getElementById('start_date');
    var paidAmount   = document.getElementById('paid_amount');

    var endDateDisplay       = document.getElementById('end_date_display');
    var originalPriceDisplay = document.getElementById('original_price_display');
    var discountDisplay      = document.getElementById('discount_display');
    var finalPriceDisplay    = document.getElementById('final_price_display');
    var remainingDisplay     = document.getElementById('remaining_amount_display');

    function fmt(n) {
        return (Math.round(n * 100) / 100).toFixed(2) + ' جنيه';
    }

    function addDays(dateStr, days) {
        var d = new Date(dateStr);
        d.setDate(d.getDate() + parseInt(days || 0, 10));
        return d.toISOString().split('T')[0];
    }

    function recalculate() {
        var planOption = planSelect.options[planSelect.selectedIndex];
        var price      = planOption ? parseFloat(planOption.getAttribute('data-price') || 0) : 0;
        var duration   = planOption ? parseInt(planOption.getAttribute('data-duration') || 0, 10) : 0;

        // تاريخ النهاية
        if (planOption && planOption.value && startDate.value) {
            endDateDisplay.value = addDays(startDate.value, duration);
        } else {
            endDateDisplay.value = '';
        }

        // الخصم
        var offerOption = offerSelect.options[offerSelect.selectedIndex];
        var discount = 0;

        if (offerOption && offerOption.value) {
            var type  = offerOption.getAttribute('data-type');
            var value = parseFloat(offerOption.getAttribute('data-value') || 0);

            discount = type === 'percentage' ? (price * (value / 100)) : value;
            discount = Math.min(discount, price);
        }

        var finalPrice = Math.max(price - discount, 0);

        originalPriceDisplay.textContent = fmt(price);
        discountDisplay.textContent      = '- ' + fmt(discount);
        finalPriceDisplay.textContent    = fmt(finalPrice);

        // المتبقي
        var paid = parseFloat(paidAmount.value || 0);
        var remaining = Math.max(finalPrice - paid, 0);
        remainingDisplay.value = remaining.toFixed(2);
    }

    [planSelect, offerSelect, startDate, paidAmount].forEach(function (el) {
        el.addEventListener('change', recalculate);
        el.addEventListener('input', recalculate);
    });

    recalculate();
})();
</script>
@endpush