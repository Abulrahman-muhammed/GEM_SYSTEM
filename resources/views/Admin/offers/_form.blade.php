{{-- resources/views/admin/offers/_form.blade.php --}}

@push('styles')
<style>
    /* Grid ثابت مش معتمد على Bootstrap RTL، بيضمن عمودين متساويين دايمًا */
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
    {{-- اسم العرض --}}
    <div>
        <label class="form-label font-weight-bold">اسم العرض <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $offer->name ?? '') }}" placeholder="مثال: عرض رمضان">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- نوع الخصم --}}
    <div>
        <label class="form-label font-weight-bold">نوع الخصم <span class="text-danger">*</span></label>
        <select name="discount_type" id="discount_type"
                class="form-control @error('discount_type') is-invalid @enderror">
            <option value="">اختر نوع الخصم...</option>
            @foreach(\App\Enums\DiscountType::cases() as $type)
                <option value="{{ $type->value }}"
                    {{ old('discount_type', $offer->discount_type?->value ?? '') === $type->value ? 'selected' : '' }}>
                    {{ $type->label() }}
                </option>
            @endforeach
        </select>
        @error('discount_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- قيمة الخصم --}}
    <div>
        <label class="form-label font-weight-bold">
            قيمة الخصم <span class="text-danger">*</span>
            <small class="text-muted font-weight-normal" id="discount-hint"></small>
        </label>
        <input type="number" step="0.01" min="0" name="discount_value" id="discount_value"
               class="form-control @error('discount_value') is-invalid @enderror"
               value="{{ old('discount_value', $offer->discount_value ?? '') }}" placeholder="0.00">
        @error('discount_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- تاريخ البداية --}}
    <div>
        <label class="form-label font-weight-bold">تاريخ البداية <span class="text-danger">*</span></label>
        <input type="date" name="start_date"
               class="form-control @error('start_date') is-invalid @enderror"
               value="{{ old('start_date', isset($offer->start_date) ? $offer->start_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- تاريخ النهاية --}}
    <div>
        <label class="form-label font-weight-bold">تاريخ النهاية <span class="text-danger">*</span></label>
        <input type="date" name="end_date"
               class="form-control @error('end_date') is-invalid @enderror"
               value="{{ old('end_date', isset($offer->end_date) ? $offer->end_date->format('Y-m-d') : '') }}">
        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- الحالة --}}
    <div>
        <label class="form-label font-weight-bold d-block">الحالة</label>
        <div class="custom-control custom-switch custom-switch-lg mt-2">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" @checked(old('status', $offer->status ?? true)) style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
            <label class="custom-control-label ms-2" for="status" style="cursor: pointer; padding-top: 0.2rem;">العرض نشط حالياً</label>
        </div>
    </div>
</div>

{{-- الوصف --}}
<div class="mb-3">
    <label class="form-label font-weight-bold">الوصف</label>
    <textarea rows="3" name="description"
              class="form-control @error('description') is-invalid @enderror"
              placeholder="تفاصيل إضافية عن العرض">{{ old('description', $offer->description ?? '') }}</textarea>
    @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
</div>

<div class="d-flex justify-content-start gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="fe fe-save"></i>
        {{ isset($offer) ? 'حفظ التعديلات' : 'حفظ العرض' }}
    </button>
    <a href="{{ route('offers.index') }}" class="btn btn-light">
        <i class="fe fe-x"></i>
        إلغاء
    </a>
</div>

@push('scripts')
<script>
    (function () {
        var select = document.getElementById('discount_type');
        var valueInput = document.getElementById('discount_value');
        var hint = document.getElementById('discount-hint');

        function updateHint() {
            if (select.value === 'percentage') {
                hint.textContent = '(نسبة مئوية، من 0 إلى 100)';
                valueInput.setAttribute('max', '100');
            } else if (select.value) {
                hint.textContent = '(قيمة ثابتة بالجنيه)';
                valueInput.removeAttribute('max');
            } else {
                hint.textContent = '';
                valueInput.removeAttribute('max');
            }
        }

        select.addEventListener('change', updateHint);
        updateHint();
    })();
</script>
@endpush