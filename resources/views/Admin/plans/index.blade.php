@extends('admin.layouts.master')
@section('title', 'خطط الاشتراك')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12 my-4">
          <div class="card shadow-sm border-0">
            <!-- Card header -->
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
              <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="icon-circle bg-primary-soft text-primary mr-3">
                  <i class="fe fe-credit-card"></i>
                </div>
                <div>
                  <h5 class="mb-0">خطط الاشتراك (الباقات)</h5>
                  <small class="text-muted">يوجد {{ $plans->total() }} خطة مسجلة</small>
                </div>
              </div>
              <a href="{{ route('plans.create') }}" class="btn btn-primary">
                <i class="fe fe-plus mr-1"></i> إضافة خطة
              </a>
            </div>

            <div class="card-body">
              <!-- Toolbar -->
              <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="text-muted mb-2 mb-md-0">
                  إجمالي الخطط: <strong>{{ $plans->total() }}</strong>
                </div>
                <x-search-toolbar :action="route('plans.index')" placeholder="ابحث باسم الخطة" />
              </div>
              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover mb-0 plans-table">
                  <thead>
                    <tr>
                      <th>اسم الخطة</th>
                      <th>السعر</th>
                      <th>المدة (يوم)</th>
                      <th class="w-25">الوصف</th>
                      <th>الحالة</th>
                      <th class="text-center">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($plans as $plan)
                      <tr>
                        <td>
                          <div>
                            <h6 class="mb-0">{{ $plan->name }}</h6>
                            <small class="text-muted">خطة #{{ $plan->id }}</small>
                          </div>
                        </td>
                        <td>
                          <span class="price-tag">{{ number_format($plan->price, 2) }} جنيه</span>
                        </td>
                        <td class="text-muted">
                          {{ $plan->duration_days }} يوم
                        </td>
                        <td class="w-25">
                          <small class="text-muted">{{ $plan->description ?? '—' }}</small>
                        </td>
                        <td>
                          @if ($plan->status)
                            <span class="badge badge-pill badge-status badge-status-active">
                              <i class="fe fe-check-circle"></i> نشطة
                            </span>
                          @else
                            <span class="badge badge-pill badge-status badge-status-inactive">
                              <i class="fe fe-x-circle"></i> غير نشطة
                            </span>
                          @endif
                        </td>
                        <td>
                          <div class="btn-group justify-content-center d-flex">
                            <a href="{{ route('plans.edit', $plan) }}"
                               class="btn btn-sm btn-icon btn-outline-primary" title="تعديل" data-toggle="tooltip">
                              <i class="fe fe-edit"></i>
                            </a>
                            <form action="{{ route('plans.destroy', $plan) }}" method="POST"
                                  class="d-inline delete-plan-form">
                              @csrf
                              @method('DELETE')
                              <button type="button"
                                      class="btn btn-sm btn-icon btn-outline-danger btn-delete-plan"
                                      data-name="{{ $plan->name }}"
                                      title="حذف" data-toggle="tooltip">
                                <i class="fe fe-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                          <i class="fe fe-credit-card d-block mb-2" style="font-size: 28px;"></i>
                          لا توجد خطط حتى الآن
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <nav aria-label="Table Paging" class="mt-3 text-muted">
                {{ $plans->links() }}
              </nav>
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
  .plans-table td,
  .plans-table th {
    vertical-align: middle;
    padding: .9rem .75rem;
  }

  .plans-table thead th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
    border-bottom: 1px solid #edf0f5;
  }

  .plans-table tbody tr {
    background: #fff;
    border-bottom: 1px solid #f1f3f8;
    transition: background-color .15s ease, transform .15s ease;
  }

  .plans-table tbody tr:last-child {
    border-bottom: none;
  }

  .plans-table tbody tr:hover {
    background: #f6f9ff;
  }

  .price-tag {
    font-weight: 600;
    color: #0d6efd;
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

  .input-group-toolbar {
    width: auto;
  }

  .input-group-toolbar .form-control {
    max-width: 220px;
  }

  .select-toolbar {
    max-width: 90px;
    border-left: 1px solid #ced4da;
    border-top-left-radius: .25rem;
    border-bottom-left-radius: .25rem;
  }

  .btn-primary {
    padding: .55rem 1rem;
    border-radius: .5rem;
  }

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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-plan').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const form = btn.closest('form');
        const planName = btn.getAttribute('data-name');

        Swal.fire({
          title: 'هل أنت متأكد؟',
          html: 'سيتم حذف خطة <strong>' + planName + '</strong> بشكل نهائي.',
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

    @if (session('success'))
      Swal.fire({
        title: 'تم بنجاح',
        text: @json(session('success')),
        icon: 'success',
        confirmButtonText: 'حسناً',
        confirmButtonColor: '#0d6efd',
        timer: 3000,
        timerProgressBar: true
      });
    @endif
  });
</script>
@endpush