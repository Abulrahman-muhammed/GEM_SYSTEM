@extends('admin.layouts.master')
@section('title', 'ملف العضو')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12 my-4">

          @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="d-flex align-items-center mb-3">
            <a href="{{ route('members.index') }}" class="btn btn-sm btn-outline-secondary mr-2">
              <i class="fe fe-arrow-right"></i> رجوع للأعضاء
            </a>
          </div>

          <div class="row">
            <!-- Card 1: معلومات العضو -->
            <div class="col-lg-7 mb-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex align-items-center py-3">
                  <div class="icon-circle bg-primary-soft text-primary mr-3">
                    <i class="fe fe-user"></i>
                  </div>
                  <div>
                    <h5 class="mb-0">معلومات العضو</h5>
                    <small class="text-muted">عضو #{{ $member->id }}</small>
                  </div>
                </div>

                <div class="card-body">
                  <div class="d-flex align-items-center mb-4">
                    <div class="avatar avatar-xl ml-3">
                      <img src="{{ $member->photo ? $member->photo_url : asset('assets/avatars/face-1.jpg') }}"
                           alt="{{ $member->full_name }}" class="avatar-img rounded-circle profile-avatar">
                    </div>
                    <div>
                      <h4 class="mb-1">{{ $member->full_name }}</h4>
                      @if ($member->status)
                        <span class="badge badge-pill badge-status badge-status-active">
                          <i class="fe fe-check-circle"></i> نشط
                        </span>
                      @else
                        <span class="badge badge-pill badge-status badge-status-inactive">
                          <i class="fe fe-x-circle"></i> غير نشط
                        </span>
                      @endif
                    </div>
                  </div>

                  <div class="info-grid">
                    <div class="info-item">
                      <span class="info-label"><i class="fe fe-hash"></i> رقم العضوية</span>
                      <span class="info-value">#{{ $member->id }}</span>
                    </div>
                    <div class="info-item">
                      <span class="info-label"><i class="fe fe-calendar"></i> العمر</span>
                      <span class="info-value">{{ $member->age ?? '—' }} سنة</span>
                    </div>
                    <div class="info-item">
                      <span class="info-label"><i class="fe fe-phone"></i> الهاتف</span>
                      <span class="info-value">
                        <a href="tel:{{ $member->phone }}" class="phone-link">{{ $member->phone }}</a>
                      </span>
                    </div>
                    <div class="info-item">
                      <span class="info-label"><i class="fe fe-users"></i> النوع</span>
                      <span class="info-value">{{ $member->gender->label() }}</span>
                    </div>
                    <div class="info-item info-item-full">
                      <span class="info-label"><i class="fe fe-map-pin"></i> العنوان</span>
                      <span class="info-value">{{ $member->address ?? '—' }}</span>
                    </div>
                    <div class="info-item">
                      <span class="info-label"><i class="fe fe-credit-card"></i> كود الباركود</span>
                      <span class="info-value">{{ $member->barcode }}</span>
                    </div>
                  </div>

                  @if ($member->notes)
                    <div class="notes-box mt-4">
                      <div class="text-muted mb-1"><i class="fe fe-file-text"></i> ملاحظات</div>
                      <div>{{ $member->notes }}</div>
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="col-lg-5">
              <!-- Card 2: الباركود -->
              <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white d-flex align-items-center py-3">
                  <div class="icon-circle bg-primary-soft text-primary mr-3">
                    <i class="fe fe-credit-card"></i>
                  </div>
                  <h5 class="mb-0">كارت العضوية</h5>
                </div>
                <div class="card-body text-center">
                  <div class="barcode-wrapper mb-2">
                    {!! $barcode !!}
                  </div>
                  <div class="barcode-code">{{ $member->barcode }}</div>
                </div>
              </div>

              <!-- Card 3: أزرار -->
              <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex align-items-center py-3">
                  <div class="icon-circle bg-primary-soft text-primary mr-3">
                    <i class="fe fe-settings"></i>
                  </div>
                  <h5 class="mb-0">الإجراءات</h5>
                </div>
                <div class="card-body">
                  <div class="d-grid action-buttons">
                    <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-primary btn-block mb-2">
                      <i class="fe fe-edit"></i> تعديل بيانات العضو
                    </a>

                    <button type="button" class="btn btn-outline-secondary btn-block mb-2" onclick="window.print()">
                      <i class="fe fe-printer"></i> طباعة الكارت
                    </button>

                    <button type="button" class="btn btn-outline-secondary btn-block mb-2" disabled
                            title="سيتم تفعيله لاحقًا">
                      <i class="fe fe-download"></i> تحميل PDF
                    </button>

                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="delete-member-form">
                      @csrf
                      @method('DELETE')
                      <button type="button" class="btn btn-outline-danger btn-block btn-delete-member"
                              data-name="{{ $member->full_name }}">
                        <i class="fe fe-trash"></i> حذف العضو
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Card 4: الاشتراك الحالي -->
            <div class="col-lg-6 mb-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex align-items-center py-3">
                  <div class="icon-circle bg-primary-soft text-primary mr-3">
                    <i class="fe fe-award"></i>
                  </div>
                  <h5 class="mb-0">الاشتراك الحالي</h5>
                </div>
                <div class="card-body">
                  @if ($currentSubscription)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <div>
                        <h5 class="mb-0">{{ $currentSubscription->plan->name ?? '—' }}</h5>
                      </div>
                      <span class="badge badge-pill badge-status {{ $currentSubscription->remaining_days >= 0 ? 'badge-status-active' : 'badge-status-inactive' }}">
                        {{ $currentSubscription->status->label() }}
                      </span>
                    </div>

                    <div class="info-grid">
                      <div class="info-item">
                        <span class="info-label"><i class="fe fe-calendar"></i> البداية</span>
                        <span class="info-value">{{ $currentSubscription->start_date?->format('d/m/Y') }}</span>
                      </div>
                      <div class="info-item">
                        <span class="info-label"><i class="fe fe-calendar"></i> النهاية</span>
                        <span class="info-value">{{ $currentSubscription->end_date?->format('d/m/Y') }}</span>
                      </div>
                      <div class="info-item">
                        <span class="info-label"><i class="fe fe-clock"></i> الأيام المتبقية</span>
                        <span class="info-value">
                          {{ $currentSubscription->remaining_days >= 0 ? $currentSubscription->remaining_days . ' يوم' : 'منتهي' }}
                        </span>
                      </div>
                      <div class="info-item">
                        <span class="info-label"><i class="fe fe-dollar-sign"></i> السعر النهائي</span>
                        <span class="info-value">{{ number_format($currentSubscription->final_price, 2) }} جنيه</span>
                      </div>
                      <div class="info-item">
                        <span class="info-label"><i class="fe fe-check"></i> المدفوع</span>
                        <span class="info-value">{{ number_format($currentSubscription->paid_amount, 2) }} جنيه</span>
                      </div>
                      <div class="info-item">
                        <span class="info-label"><i class="fe fe-alert-circle"></i> المتبقي</span>
                        <span class="info-value {{ $currentSubscription->remaining_amount > 0 ? 'text-danger' : '' }}">
                          {{ number_format($currentSubscription->remaining_amount, 2) }} جنيه
                        </span>
                      </div>
                    </div>
                  @else
                    <div class="text-center text-muted py-4">
                      <i class="fe fe-award d-block mb-2" style="font-size: 24px;"></i>
                      لا يوجد اشتراك مسجل لهذا العضو
                    </div>
                  @endif
                </div>
              </div>
            </div>

            <!-- Card 5: آخر المدفوعات -->
            <div class="col-lg-6 mb-4">
              <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white d-flex align-items-center py-3">
                  <div class="icon-circle bg-primary-soft text-primary mr-3">
                    <i class="fe fe-dollar-sign"></i>
                  </div>
                  <h5 class="mb-0">آخر المدفوعات</h5>
                </div>
                <div class="card-body p-0">
                  @forelse ($recentPayments as $payment)
                    <div class="payment-row d-flex justify-content-between align-items-center">
                      <div>
                        <div class="font-weight-bold">{{ number_format($payment->amount, 2) }} جنيه</div>
                        <small class="text-muted">{{ $payment->method->label() }}</small>
                      </div>
                      <small class="text-muted">{{ $payment->payment_date?->format('d/m/Y') }}</small>
                    </div>
                  @empty
                    <div class="text-center text-muted py-4">
                      <i class="fe fe-inbox d-block mb-2" style="font-size: 24px;"></i>
                      لا توجد مدفوعات مسجلة
                    </div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Card 6: سجل الحضور -->
            <div class="col-12 mb-4">
              <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
                  <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="icon-circle bg-primary-soft text-primary mr-3">
                      <i class="fe fe-clock"></i>
                    </div>
                    <div>
                      <h5 class="mb-0">سجل الحضور</h5>
                      <small class="text-muted">
                        إجمالي الزيارات: {{ $attendanceStats['total_visits'] }}
                        @if ($attendanceStats['last_visit'])
                          — آخر حضور: {{ $attendanceStats['last_visit']->format('d/m/Y — h:i A') }}
                        @endif
                      </small>
                    </div>
                  </div>
                  <a href="{{ route('attendances.index', ['search' => $member->full_name]) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fe fe-list mr-1"></i> السجل الكامل
                  </a>
                </div>

                <div class="card-body p-0">
                  @forelse ($recentAttendances as $attendance)
                    <div class="attendance-row d-flex justify-content-between align-items-center">
                      <div class="d-flex align-items-center">
                        <div class="attendance-date-badge">
                          {{ $attendance->date->format('d') }}
                          <small>{{ $attendance->date->translatedFormat('M') }}</small>
                        </div>
                        <div>
                          <div class="font-weight-bold">
                            {{ $attendance->check_in->format('h:i A') }}
                            @if ($attendance->check_out)
                               {{ $attendance->check_out->format('h:i A') }}
                            @endif
                          </div>
                          <small class="text-muted">{{ $attendance->duration_label }}</small>
                        </div>
                      </div>

                      @if ($attendance->is_checked_in)
                        <span class="badge badge-pill badge-status badge-status-active">
                          <i class="fe fe-check-circle"></i> جوه دلوقتي
                        </span>
                      @else
                        <span class="badge badge-pill badge-status badge-status-inactive">
                          انصرف
                        </span>
                      @endif
                    </div>
                  @empty
                    <div class="text-center text-muted py-4">
                      <i class="fe fe-clock d-block mb-2" style="font-size: 24px;"></i>
                      لا يوجد سجل حضور لهذا العضو بعد
                    </div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  .icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .bg-primary-soft {
    background-color: rgba(13, 110, 253, .1);
  }

  .card {
    border-radius: .9rem;
    overflow: hidden;
  }

  .profile-avatar {
    width: 72px;
    height: 72px;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #eef0f4;
  }

  .badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: .4rem .75rem;
    font-weight: 500;
    font-size: .78rem;
  }

  .badge-status-active {
    background-color: rgba(25, 135, 84, .12);
    color: #198754;
  }

  .badge-status-inactive {
    background-color: rgba(108, 117, 125, .12);
    color: #6c757d;
  }

  .info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem 1.5rem;
  }

  .info-item-full {
    grid-column: 1 / -1;
  }

  .info-label {
    display: block;
    color: #6b7280;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-bottom: 4px;
  }

  .info-value {
    display: block;
    font-weight: 600;
    color: #1f2937;
  }

  .phone-link {
    color: #1f2937;
    text-decoration: none;
  }

  .phone-link:hover {
    color: #0d6efd;
  }

  .notes-box {
    background: #f8f9fc;
    border-radius: .6rem;
    padding: 1rem;
    font-size: .9rem;
  }

  .barcode-wrapper svg {
    max-width: 100%;
    height: auto;
  }

  .barcode-code {
    font-family: monospace;
    letter-spacing: .1em;
    color: #6b7280;
    font-size: .9rem;
  }

  .action-buttons .btn {
    text-align: right;
    border-radius: .5rem;
    padding: .65rem 1rem;
  }

  .action-buttons .btn i {
    margin-left: 8px;
  }

  .payment-row {
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #f1f3f8;
  }

  .payment-row:last-child {
    border-bottom: none;
  }

  .attendance-row {
    padding: .9rem 1.25rem;
    border-bottom: 1px solid #f1f3f8;
  }

  .attendance-row:last-child {
    border-bottom: none;
  }

  .attendance-date-badge {
    width: 46px;
    height: 46px;
    border-radius: .6rem;
    background: #f8f9fc;
    color: #1f2937;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: .95rem;
    margin-left: 14px;
    flex-shrink: 0;
  }

  .attendance-date-badge small {
    font-weight: 500;
    font-size: .65rem;
    color: #6b7280;
    text-transform: uppercase;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-member').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const form = btn.closest('form');
        const memberName = btn.getAttribute('data-name');

        Swal.fire({
          title: 'هل أنت متأكد؟',
          html: 'سيتم حذف العضو <strong>' + memberName + '</strong> بشكل نهائي.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'نعم، احذف',
          cancelButtonText: 'إلغاء',
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          reverseButtons: true,
          focusCancel: true
        }).then(function (result) {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush