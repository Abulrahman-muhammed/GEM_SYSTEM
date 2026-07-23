@extends('admin.layouts.master')
@section('title', 'المدفوعات')
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
                  <i class="fe fe-dollar-sign"></i>
                </div>
                <div>
                  <h5 class="mb-0">المدفوعات</h5>
                  <small class="text-muted">يوجد {{ $payments->total() }} دفعة مسجلة</small>
                </div>
              </div>
              <a href="{{ route('payments.create') }}" class="btn btn-primary">
                <i class="fe fe-plus mr-1"></i> تسجيل دفعة
              </a>
            </div>

            <div class="card-body">
              <!-- Toolbar -->
              <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="text-muted mb-2 mb-md-0">
                  إجمالي المدفوعات: <strong>{{ $payments->total() }}</strong>
                </div>

                <x-search-toolbar :action="route('payments.index')" placeholder="ابحث باسم العضو أو رقم الهاتف" />
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover mb-0 payments-table">
                  <thead>
                    <tr>
                      <th>العضو</th>
                      <th>الخطة</th>
                      <th>المبلغ</th>
                      <th>طريقة الدفع</th>
                      <th>تاريخ الدفع</th>
                      <th>ملاحظات</th>
                      <th class="text-center">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($payments as $payment)
                      <tr>
                        <td>
                          <div>
                            <h6 class="mb-0">{{ $payment->subscription->member->full_name }}</h6>
                            <small class="text-muted">{{ $payment->subscription->member->phone }}</small>
                          </div>
                        </td>
                        <td class="text-muted">{{ $payment->subscription->plan->name }}</td>
                        <td>
                          <span class="price-tag">{{ number_format($payment->amount, 2) }} جنيه</span>
                        </td>
                        <td class="text-muted">
                          <i class="fe {{ $payment->method->icon() }} mr-1"></i>
                          {{ $payment->method->label() }}
                        </td>
                        <td class="text-muted">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td class="w-25">
                          <small class="text-muted">{{ $payment->notes ?? '—' }}</small>
                        </td>
                        <td class="text-center">
                            @if ($payment->subscription && $payment->subscription->member)
                              <a href="{{ route('payments.invoice.print', $payment) }}"
                                class="btn btn-sm btn-icon btn-outline-primary"
                                title="عرض فاتورة"
                                target="_blank">
                                  <i class="fe fe-printer"></i>
                              </a>
                          @endif
                          <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                class="d-inline delete-payment-form">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-sm btn-icon btn-outline-danger btn-delete-payment"
                                    data-name="{{ $payment->subscription->member->full_name }}"
                                    title="حذف" data-toggle="tooltip">
                              <i class="fe fe-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                          <i class="fe fe-dollar-sign d-block mb-2" style="font-size: 28px;"></i>
                          لا توجد مدفوعات حتى الآن
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <nav aria-label="Table Paging" class="mt-3 text-muted">
                {{ $payments->links() }}
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
  .payments-table td,
  .payments-table th {
    vertical-align: middle;
    padding: .9rem .75rem;
  }

  .payments-table thead th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
    border-bottom: 1px solid #edf0f5;
  }

  .payments-table tbody tr {
    background: #fff;
    border-bottom: 1px solid #f1f3f8;
  }

  .payments-table tbody tr:last-child { border-bottom: none; }
  .payments-table tbody tr:hover { background: #f6f9ff; }

  .price-tag { font-weight: 600; color: #198754; }

  .btn-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    border-radius: .5rem;
  }

  .input-group-toolbar { width: auto; }
  .input-group-toolbar .form-control { max-width: 220px; }

  .select-toolbar {
    max-width: 90px;
    border-left: 1px solid #ced4da;
    border-top-left-radius: .25rem;
    border-bottom-left-radius: .25rem;
  }

  .btn-primary { padding: .55rem 1rem; border-radius: .5rem; }

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

  .card { border-radius: .9rem; overflow: hidden; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-delete-payment').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const form = btn.closest('form');
        const name = btn.getAttribute('data-name');

        Swal.fire({
          title: 'هل أنت متأكد؟',
          html: 'سيتم حذف دفعة العضو <strong>' + name + '</strong> نهائيًا.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'نعم، احذف',
          cancelButtonText: 'إلغاء',
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          reverseButtons: true,
          focusCancel: true
        }).then(function (result) {
          if (result.isConfirmed) form.submit();
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