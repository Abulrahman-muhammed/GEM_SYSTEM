@extends('admin.layouts.master')
@section('title', 'تسجيل دفعة')
@section('content')
<div class="container-fluid">
    <h2 class="h4 mb-3">تسجيل دفعة جديدة</h2>
    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf

                <div class="form-grid-2">
                    {{-- الاشتراك --}}
                    <div>
                        <label class="form-label font-weight-bold">الاشتراك <span class="text-danger">*</span></label>
                        <select name="subscription_id" class="form-control @error('subscription_id') is-invalid @enderror">
                            <option value="">اختر الاشتراك...</option>
                            @foreach ($subscriptions as $subscription)
                                <option value="{{ $subscription->id }}"
                                    {{ old('subscription_id', $selectedSubscriptionId) == $subscription->id ? 'selected' : '' }}>
                                    {{ $subscription->member->full_name }} — {{ $subscription->plan->name }}
                                    (متبقي {{ number_format($subscription->remaining_amount, 2) }} جنيه)
                                </option>
                            @endforeach
                        </select>
                        @error('subscription_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- المبلغ --}}
                    <div>
                        <label class="form-label font-weight-bold">المبلغ <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.01" name="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               value="{{ old('amount') }}" placeholder="0.00">
                        @error('amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- تاريخ الدفع --}}
                    <div>
                        <label class="form-label font-weight-bold">تاريخ الدفع <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date"
                               class="form-control @error('payment_date') is-invalid @enderror"
                               value="{{ old('payment_date', now()->format('Y-m-d')) }}">
                        @error('payment_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- طريقة الدفع --}}
                    <div>
                        <label class="form-label font-weight-bold d-block">طريقة الدفع <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            @foreach(\App\Enums\PaymentMethod::cases() as $method)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="method"
                                           id="method_{{ $method->value }}" value="{{ $method->value }}"
                                           {{ old('method', 'cash') === $method->value ? 'checked' : '' }}>
                                    <label class="form-check-label" for="method_{{ $method->value }}">
                                        <i class="fe {{ $method->icon() }} me-1"></i> {{ $method->label() }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('method') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- ملاحظات --}}
                <div class="mb-3">
                    <label class="form-label font-weight-bold">ملاحظات</label>
                    <textarea rows="2" name="notes"
                              class="form-control @error('notes') is-invalid @enderror"
                              placeholder="أي ملاحظات على الدفعة">{{ old('notes') }}</textarea>
                    @error('notes') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-start gap-2 mt-4">
                    <button class="btn btn-primary">
                        <i class="fe fe-save"></i> حفظ الدفعة
                    </button>
                    <a href="{{ route('payments.index') }}" class="btn btn-light">
                        <i class="fe fe-x"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
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
</style>
@endpush