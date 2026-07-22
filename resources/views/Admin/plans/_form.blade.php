@push('styles')
<style>
    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 1.5rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 767px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<div class="form-grid-2">
    {{-- اسم الخطة --}}
    <div>
        <label class="form-label font-weight-bold">اسم الخطة <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $plan->name ?? '') }}" placeholder="مثال: خطة شهرية">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- السعر --}}
    <div>
        <label class="form-label font-weight-bold">السعر (جنيه) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="price"
               class="form-control @error('price') is-invalid @enderror"
               value="{{ old('price', $plan->price ?? '') }}" placeholder="0.00">
        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- مدة الاشتراك --}}
    <div>
        <label class="form-label font-weight-bold">مدة الاشتراك (بالأيام) <span class="text-danger">*</span></label>
        <input type="number" min="1" name="duration_days"
               class="form-control @error('duration_days') is-invalid @enderror"
               value="{{ old('duration_days', $plan->duration_days ?? '') }}" placeholder="مثال: 30">
        @error('duration_days') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- الحالة --}}
    <div>
        <label class="form-label font-weight-bold d-block">حالة الخطة</label>
        <div class="custom-control custom-switch custom-switch-lg mt-2">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" @checked(old('status', $plan->status ?? true)) style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
            <label class="custom-control-label ms-2" for="status" style="cursor: pointer; padding-top: 0.2rem;">الخطة نشطة حالياً</label>
        </div>
    </div>
</div>

{{-- الوصف (خارج الـ Grid ليكون بعرض الصفحة بالكامل) --}}
<div class="mb-3">
    <label class="form-label font-weight-bold">الوصف</label>
    <textarea rows="3" name="description"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="تفاصيل إضافية عن الخطة">{{ old('description', $plan->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="d-flex justify-content-start gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="fe fe-save"></i>
        {{ isset($plan) ? 'حفظ التعديلات' : 'حفظ الخطة' }}
    </button>
    <a href="{{ route('plans.index') }}" class="btn btn-light">
        <i class="fe fe-x"></i>
        إلغاء
    </a>
</div>