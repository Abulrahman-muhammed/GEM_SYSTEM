@extends('admin.layouts.master')
@section('title', 'سجل الحضور والانصراف')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12 my-4">

          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- إحصائيات اليوم --}}
          <div class="row mb-3">
            <div class="col-md-4 mb-3 mb-md-0">
              <div class="stat-card stat-card-primary">
                <div class="stat-icon"><i class="fe fe-users"></i></div>
                <div>
                  <div class="stat-value">{{ $stats['total_today'] }}</div>
                  <div class="stat-label">إجمالي حضور اليوم</div>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
              <div class="stat-card stat-card-success">
                <div class="stat-icon"><i class="fe fe-log-in"></i></div>
                <div>
                  <div class="stat-value">{{ $stats['still_in'] }}</div>
                  <div class="stat-label">جوه دلوقتي</div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="stat-card stat-card-secondary">
                <div class="stat-icon"><i class="fe fe-log-out"></i></div>
                <div>
                  <div class="stat-value">{{ $stats['checked_out'] }}</div>
                  <div class="stat-label">انصرفوا</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card shadow-sm border-0">
            <!-- Card header -->
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
              <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="icon-circle bg-primary-soft text-primary mr-3">
                  <i class="fe fe-clock"></i>
                </div>
                <div>
                  <h5 class="mb-0">سجل الحضور والانصراف</h5>
                  <small class="text-muted">يوجد {{ $attendances->total() }} سجل مطابق</small>
                </div>
              </div>
              <a href="{{ route('attendances.scan') }}" class="btn btn-primary">
                <i class="fe fe-camera me-1"></i> شاشة السكانر
              </a>
            </div>

            <div class="card-body">
              {{-- فلاتر --}}
              <form method="GET" action="{{ route('attendances.index') }}" class="row g-2 align-items-end mb-4">
                <div class="col-md-4">
                  <label class="form-label small text-muted">بحث</label>
                  <div class="input-group input-group-toolbar w-100">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white border-right-0">
                        <i class="fe fe-search"></i>
                      </span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control border-left-0" placeholder="اسم العضو / الهاتف / الباركود">
                  </div>
                </div>
                <div class="col-md-3">
                  <label class="form-label small text-muted">التاريخ</label>
                  <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}" class="form-control">
                </div>
                <div class="col-md-3">
                  <label class="form-label small text-muted">الحالة</label>
                  <select name="status" class="form-select">
                    <option value="">الكل</option>
                    <option value="in" @selected(request('status') === 'in')>جوه دلوقتي</option>
                    <option value="out" @selected(request('status') === 'out')>انصرف</option>
                  </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                  <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fe fe-search"></i> بحث
                  </button>
                  <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary" title="إعادة تعيين">
                    <i class="fe fe-rotate-ccw"></i>
                  </a>
                </div>
              </form>

              {{-- الجدول --}}
              <div class="table-responsive">
                <table class="table table-hover mb-0 attendance-table">
                  <thead>
                    <tr>
                      <th>العضو</th>
                      <th>التاريخ</th>
                      <th>وقت الحضور</th>
                      <th>وقت الانصراف</th>
                      <th>المدة</th>
                      <th>الحالة</th>
                      <th class="text-center">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($attendances as $attendance)
                      <tr>
                        <td>
                          <div class="d-flex align-items-center">
                            <img src="{{ $attendance->member->photo_url }}" class="attendance-avatar rounded-circle me-2" alt="{{ $attendance->member->full_name }}">
                            <div>
                              <h6 class="mb-0">{{ $attendance->member->full_name }}</h6>
                              <small class="text-muted">{{ $attendance->member->barcode }}</small>
                            </div>
                          </div>
                        </td>
                        <td class="text-muted">{{ $attendance->date->format('Y-m-d') }}</td>
                        <td class="text-muted">{{ $attendance->check_in?->format('h:i A') }}</td>
                        <td class="text-muted">{{ $attendance->check_out?->format('h:i A') ?? '—' }}</td>
                        <td class="text-muted">{{ $attendance->duration_label ?? '—' }}</td>
                        <td>
                          @if ($attendance->is_checked_in)
                            <span class="badge badge-pill badge-status badge-status-active">
                              <i class="fe fe-check-circle"></i> جوه دلوقتي
                            </span>
                          @else
                            <span class="badge badge-pill badge-status badge-status-inactive">
                              <i class="fe fe-x-circle"></i> انصرف
                            </span>
                          @endif
                        </td>
                        <td>
                          <div class="btn-group justify-content-center d-flex">
                            @if ($attendance->is_checked_in)
                              <form action="{{ route('attendances.force-checkout', $attendance) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-icon btn-outline-warning" title="تسجيل انصراف يدوي" data-toggle="tooltip">
                                  <i class="fe fe-log-out"></i>
                                </button>
                              </form>
                            @endif
                            <button type="button"
                                    class="btn btn-sm btn-icon btn-outline-danger btn-delete-attendance"
                                    data-url="{{ route('attendances.destroy', $attendance) }}"
                                    title="حذف السجل" data-toggle="tooltip">
                              <i class="fe fe-trash"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                          <i class="fe fe-clock d-block mb-2" style="font-size: 28px;"></i>
                          لا يوجد سجلات حضور مطابقة
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              @if ($attendances->hasPages())
                <nav class="mt-3 text-muted">{{ $attendances->links() }}</nav>
              @endif
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- فورم مخفي لتنفيذ الحذف بعد تأكيد SweetAlert --}}
<form id="deleteAttendanceForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  .stat-card {
    display: flex;
    align-items: center;
    gap: 14px;
    background: #fff;
    border: 0;
    border-radius: .9rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    padding: 1.1rem 1.25rem;
  }

  .stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .stat-card-primary .stat-icon   { background: rgba(13, 110, 253, .1);  color: #0d6efd; }
  .stat-card-success .stat-icon   { background: rgba(25, 135, 84, .12);  color: #198754; }
  .stat-card-secondary .stat-icon { background: rgba(108, 117, 125, .12); color: #6c757d; }

  .stat-value { font-size: 1.5rem; font-weight: 700; line-height: 1.1; }
  .stat-label { font-size: .8rem; color: #6b7280; }

  .attendance-table td,
  .attendance-table th {
    vertical-align: middle;
    padding: .9rem .75rem;
  }

  .attendance-table thead th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
    border-bottom: 1px solid #edf0f5;
  }

  .attendance-table tbody tr {
    background: #fff;
    border-bottom: 1px solid #f1f3f8;
  }

  .attendance-table tbody tr:last-child { border-bottom: none; }
  .attendance-table tbody tr:hover { background: #f6f9ff; }

  .attendance-avatar {
    width: 36px;
    height: 36px;
    object-fit: cover;
  }

  .badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: .4rem .75rem;
    font-weight: 500;
    font-size: .78rem;
  }

  .badge-status-active   { background-color: rgba(25, 135, 84, .12); color: #198754; }
  .badge-status-inactive { background-color: rgba(108, 117, 125, .12); color: #6c757d; }

  .btn-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: .5rem;
    margin: 0 2px;
  }

  .input-group-toolbar .form-control { max-width: none; }

  .icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
  }

  .bg-primary-soft { background-color: rgba(13, 110, 253, .1); }

  .btn-primary { padding: .55rem 1rem; border-radius: .5rem; }

  .card { border-radius: .9rem; overflow: hidden; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-attendance').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const url = btn.dataset.url;

            if (window.Swal) {
                Swal.fire({
                    title: 'متأكد من حذف السجل؟',
                    text: 'مش هينفع ترجّعه تاني.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'أيوه احذف',
                    cancelButtonText: 'لأ',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteAttendanceForm');
                        form.action = url;
                        form.submit();
                    }
                });
            } else if (confirm('متأكد من حذف السجل؟')) {
                const form = document.getElementById('deleteAttendanceForm');
                form.action = url;
                form.submit();
            }
        });
    });
});
</script>
@endpush