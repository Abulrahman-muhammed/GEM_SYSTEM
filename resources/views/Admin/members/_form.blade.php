<div class="row">
    {{-- الاسم ورقم الهاتف --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">الاسم بالكامل <span class="text-danger">*</span></label>
        <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
               value="{{ old('full_name', $member->full_name ?? '') }}" placeholder="اسم العضو">
        @error('full_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
               value="{{ old('phone', $member->phone ?? '') }}" placeholder="01xxxxxxxxx">
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- تاريخ الميلاد والعنوان --}}
    <div class="col-md-6 mb-3">
        <label class="form-label">تاريخ الميلاد</label>
        <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" 
               value="{{ old('birth_date', isset($member->birth_date) ? $member->birth_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">العنوان</label>
        <input type="text" name="address" class="form-control" value="{{ old('address', $member->address ?? '') }}" placeholder="العنوان">
    </div>

    {{-- النوع --}}
    <div class="col-12 mb-3">
        <label class="form-label d-block">النوع</label>
        <div class="d-flex gap-4">
            @foreach(App\Enums\Gender::cases() as $gender)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="gender_{{ $gender->value }}" 
                           value="{{ $gender->value }}" @checked(old('gender', $member->gender?->value ?? 'male') == $gender->value)>
                    <label class="form-check-label" for="gender_{{ $gender->value }}">
                        {{ $gender->emoji() }} {{ $gender->label() }}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>

<hr class="my-4">

{{-- الصورة --}}
<div class="row align-items-center mb-4">
    <div class="col-md-2 text-center">
        <label for="photo-input" style="cursor:pointer;">
            <img id="preview-img" src="{{ isset($member) && $member->photo ? asset('storage/'.$member->photo) : asset('assets/avatars/face-1.jpg') }}" 
                 class="rounded-circle border" width="100" height="100" style="object-fit:cover">
        </label>
    </div>
    <div class="col-md-10">
        <h6 class="mb-1">الصورة الشخصية</h6>
        <p class="text-muted small">اضغط على الصورة لتغييرها (يُفضل صيغ الصور الشائعة)</p>
        <input type="file" id="photo-input" name="photo" class="form-control" accept="image/*" onchange="previewPhoto(this)">
    </div>
</div>

<hr>

{{-- الملاحظات والحالة --}}
<div class="mb-3">
    <label class="form-label">ملاحظات</label>
    <textarea rows="3" name="notes" class="form-control" placeholder="أي ملاحظات إضافية">{{ old('notes', $member->notes ?? '') }}</textarea>
</div>

<div class="mb-4">
    <div class="custom-control custom-switch custom-switch-lg">
        <!-- Hidden input يضمن إرسال قيمة 0 لو الـ Checkbox غير مفعّل -->
        <input type="hidden" name="status" value="0">
        <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" @checked(old('status', $member->status ?? true)) style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
        <label class="custom-control-label" for="status" style="cursor: pointer; padding-top: 0.2rem;">العضو نشط حالياً</label>
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary px-4">
        <i class="fe fe-save"></i> {{ isset($member) ? 'حفظ التعديلات' : 'حفظ العضو' }}
    </button>
    <a href="{{ route('members.index') }}" class="btn btn-secondary">إلغاء</a>
</div>

@push('scripts')
<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $('#preview-img').attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush