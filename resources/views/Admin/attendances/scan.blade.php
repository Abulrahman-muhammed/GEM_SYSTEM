@extends('admin.layouts.master')

@section('title', 'تسجيل الحضور والانصراف')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <div class="d-flex align-items-center justify-content-between my-4">
                <h4 class="mb-0">
                    <i class="bi bi-fingerprint me-2"></i> تسجيل الحضور والانصراف
                </h4>
                <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-list-ul"></i> سجل اليوم
                </a>
            </div>

            {{-- كارت السكانر --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div id="scanIcon" class="icon-circle mx-auto mb-3" style="width:90px;height:90px;font-size:2.5rem;">
                        <i class="bi bi-upc-scan"></i>
                    </div>

                    <h5 class="mb-1">امسح باركود العضو</h5>
                    <p class="text-muted mb-4">هيتم تسجيل الحضور أو الانصراف تلقائيًا حسب حالة العضو</p>

                    <form id="scanForm" autocomplete="off">
                        @csrf
                        <input
                            type="text"
                            id="barcodeInput"
                            name="barcode"
                            class="form-control form-control-lg text-center mx-auto"
                            style="max-width: 420px; letter-spacing: 2px;"
                            placeholder="امسح الكود أو اكتبه يدويًا..."
                            autofocus
                        >
                    </form>
                </div>
            </div>

            {{-- كارت آخر عملية --}}
            <div id="resultCard" class="card shadow-sm d-none">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img id="resultPhoto" src="" alt="" class="rounded-circle me-3" style="width:64px;height:64px;object-fit:cover;">
                        <div class="flex-grow-1">
                            <h6 id="resultName" class="mb-1"></h6>
                            <span id="resultBadge" class="badge-status"></span>
                        </div>
                        <div class="text-end">
                            <div id="resultTime" class="fw-bold fs-5"></div>
                            <small id="resultDuration" class="text-muted"></small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- إحصائية سريعة --}}
            <div class="row mt-4 text-center">
                <div class="col-4">
                    <div class="card shadow-sm py-3">
                        <div class="fs-4 fw-bold text-primary" id="statTotal">0</div>
                        <small class="text-muted">حضروا اليوم</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-sm py-3">
                        <div class="fs-4 fw-bold text-success" id="statIn">0</div>
                        <small class="text-muted">جوه دلوقتي</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card shadow-sm py-3">
                        <div class="fs-4 fw-bold text-secondary" id="statOut">0</div>
                        <small class="text-muted">انصرفوا</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f3f9;
        color: #6c63ff;
        transition: all .25s ease;
    }
    .icon-circle.success { background: #e6f7ec; color: #1e8e4d; }
    .icon-circle.leaving { background: #fdf2e3; color: #c8791a; }
    .icon-circle.error   { background: #fde8e8; color: #c62828; }

    #resultCard { animation: fadeIn .25s ease; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input       = document.getElementById('barcodeInput');
    const scanIcon    = document.getElementById('scanIcon');
    const resultCard  = document.getElementById('resultCard');
    const resultPhoto = document.getElementById('resultPhoto');
    const resultName  = document.getElementById('resultName');
    const resultBadge = document.getElementById('resultBadge');
    const resultTime  = document.getElementById('resultTime');
    const resultDuration = document.getElementById('resultDuration');

    const statTotal = document.getElementById('statTotal');
    const statIn    = document.getElementById('statIn');
    const statOut   = document.getElementById('statOut');

    let submitting = false;

    function refreshStats() {
        fetch("{{ route('attendances.index') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        // الإحصائيات بترجع مع أول طلب سكان أو ممكن تعمل route مخصص لو حابب تحدثها لايف
    }

    function setIconState(state) {
        scanIcon.classList.remove('success', 'leaving', 'error');
        if (state) scanIcon.classList.add(state);
    }

    function focusInput() {
        input.value = '';
        input.focus();
    }

    async function submitBarcode(barcode) {
        if (submitting || !barcode) return;
        submitting = true;

        try {
            const res = await fetch("{{ route('attendances.scan.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ barcode }),
            });

            const data = await res.json();

            resultCard.classList.remove('d-none');

            if (!data.ok) {
                setIconState('error');
                resultPhoto.src = data.member?.photo ?? '{{ asset("assets/avatars/face-1.jpg") }}';
                resultName.textContent = data.member?.name ?? 'كود غير معروف';
                resultBadge.textContent = data.message;
                resultBadge.className = 'badge-status bg-danger text-white';
                resultTime.textContent = '';
                resultDuration.textContent = '';
                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'خطأ', text: data.message, timer: 1800, showConfirmButton: false });
                }
            } else if (data.action === 'check_in') {
                setIconState('success');
                resultPhoto.src = data.member.photo;
                resultName.textContent = data.member.name;
                resultBadge.textContent = 'تم تسجيل الحضور';
                resultBadge.className = 'badge-status bg-success text-white';
                resultTime.textContent = data.time;
                resultDuration.textContent = 'أهلاً بيك 👋';
                statTotal.textContent = parseInt(statTotal.textContent) + 1;
                statIn.textContent = parseInt(statIn.textContent) + 1;
            } else {
                setIconState('leaving');
                resultPhoto.src = data.member.photo;
                resultName.textContent = data.member.name;
                resultBadge.textContent = 'تم تسجيل الانصراف';
                resultBadge.className = 'badge-status bg-warning text-dark';
                resultTime.textContent = data.time;
                resultDuration.textContent = data.duration ? ('مدة الزيارة: ' + data.duration) : '';
                statIn.textContent = Math.max(0, parseInt(statIn.textContent) - 1);
                statOut.textContent = parseInt(statOut.textContent) + 1;
            }
        } catch (e) {
            if (window.Swal) {
                Swal.fire({ icon: 'error', title: 'خطأ في الاتصال', text: 'اتأكد من الشبكة وحاول تاني.' });
            }
        } finally {
            submitting = false;
            setTimeout(() => setIconState(null), 1200);
            focusInput();
        }
    }

    // العادة: قارئ الباركود بيكتب الأرقام بسرعة ثم يبعت Enter
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitBarcode(input.value.trim());
        }
    });

    document.getElementById('scanForm').addEventListener('submit', function (e) {
        e.preventDefault();
        submitBarcode(input.value.trim());
    });

    // خلي الفوكس دايمًا على الحقل حتى لو المستخدم دوس في أي حتة تانية بالصفحة
    document.addEventListener('click', function (e) {
        if (!resultCard.contains(e.target)) {
            input.focus();
        }
    });

    focusInput();
});
</script>
@endpush