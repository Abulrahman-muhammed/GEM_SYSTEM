@extends('admin.layouts.master')
@section('title', 'الاشتراكات')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12 my-4">

          @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="card shadow-sm border-0">
            <!-- Card header -->
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
              <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="icon-circle bg-primary-soft text-primary mr-3">
                  <i class="fe fe-repeat"></i>
                </div>
                <div>
                  <h5 class="mb-0">الاشتراكات</h5>
                  <small class="text-muted">يوجد {{ $subscriptions->total() }} اشتراك مسجل</small>
                </div>
              </div>
              <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
                <i class="fe fe-plus mr-1"></i> اشتراك جديد
              </a>
            </div>

            <div class="card-body">
              <!-- Toolbar -->
              <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="text-muted mb-2 mb-md-0">
                  إجمالي الاشتراكات: <strong>{{ $subscriptions->total() }}</strong>
                </div>

                <form class="d-flex align-items-center" method="GET" action="{{ route('subscriptions.index') }}">
                  <div class="input-group input-group-toolbar">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white border-right-0">
                        <i class="fe fe-search"></i>
                      </span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" id="search"
                           value="{{ request('search') }}" placeholder="ابحث باسم العضو أو رقم الهاتف">
                    <select name="per_page" id="perPage" class="custom-select select-toolbar"
                            onchange="this.form.submit()">
                      <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                      <option value="32" {{ request('per_page', 32) == 32 ? 'selected' : '' }}>32</option>
                      <option value="64" {{ request('per_page') == 64 ? 'selected' : '' }}>64</option>
                      <option value="128" {{ request('per_page') == 128 ? 'selected' : '' }}>128</option>
                    </select>
                  </div>
                </form>
              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover mb-0 subscriptions-table">
                  <thead>
                    <tr>
                      <th>العضو</th>
                      <th>الخطة</th>
                      <th>العرض</th>
                      <th>البداية</th>
                      <th>النهاية</th>
                      <th>المتبقي</th>
                      <th>الحالة</th>
                      <th class="text-center">العمليات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($subscriptions as $subscription)
                      <tr>
                        <td>
                          <div>
                            <h6 class="mb-0">{{ $subscription->member->full_name }}</h6>
                            <small class="text-muted">{{ $subscription->member->phone }}</small>
                          </div>
                        </td>
                        <td class="text-muted">{{ $subscription->plan->name }}</td>
                        <td class="text-muted">{{ $subscription->offer?->name ?? '—' }}</td>
                        <td class="text-muted">{{ $subscription->start_date->format('Y-m-d') }}</td>
                        <td class="text-muted">{{ $subscription->end_date->format('Y-m-d') }}</td>
                        <td>
                          @if ($subscription->remaining_amount > 0)
                            <span class="price-tag text-danger">{{ number_format($subscription->remaining_amount, 2) }} جنيه</span>
                          @else
                            <span class="text-success">مدفوع بالكامل</span>
                          @endif
                        </td>
                        <td>
                          @switch($subscription->status->value)
                            @case('active')
                              <span class="badge badge-pill badge-status badge-status-active">🟢 نشط</span>
                              @break
                            @case('frozen')
                              <span class="badge badge-pill badge-status badge-status-frozen">🟡 مجمد</span>
                              @break
                            @case('expired')
                              <span class="badge badge-pill badge-status badge-status-expired">🔴 منتهي</span>
                              @break
                            @case('cancelled')
                              <span class="badge badge-pill badge-status badge-status-cancelled">⚫ ملغي</span>
                              @break
                          @endswitch
                        </td>
                        <td class="text-center">
                          <div class="dropdown">
                            <button class="btn btn-sm btn-icon btn-outline-secondary dropdown-toggle" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <i class="fe fe-more-vertical"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                              @if ($subscription->payments->isNotEmpty())
                                <a class="dropdown-item" href="{{ route('payments.invoice.print', $subscription->payments->first()) }}" target="_blank">
                                  <i class="fe fe-printer mr-1"></i> طباعة فاتورة
                                </a>
                              @endif

                              @if ($subscription->status->value !== 'cancelled')
                                <form action="{{ route('subscriptions.renew', $subscription) }}" method="POST">
                                  @csrf
                                  <button type="submit" class="dropdown-item">
                                    <i class="fe fe-refresh-cw mr-1"></i> تجديد الاشتراك
                                  </button>
                                </form>
                              @endif

                              @if ($subscription->status->value === 'active')
                                  <button type="button" class="dropdown-item btn-freeze-subscription"
                                          data-url="{{ route('subscriptions.freeze', $subscription) }}"
                                          data-name="{{ $subscription->member->full_name }}"
                                          data-end-date="{{ $subscription->end_date->format('Y-m-d') }}">
                                    <i class="fe fe-pause mr-1"></i> تجميد الاشتراك
                                  </button>
                              @elseif ($subscription->status->value === 'frozen')
                                <form action="{{ route('subscriptions.unfreeze', $subscription) }}" method="POST">
                                  @csrf
                                  <button type="submit" class="dropdown-item">
                                    <i class="fe fe-play mr-1"></i> إلغاء التجميد
                                  </button>
                                </form>
                              @endif

                              @if (! in_array($subscription->status->value, ['cancelled', 'expired']))
                                <div class="dropdown-divider"></div>
                                <button type="button" class="dropdown-item text-danger btn-cancel-subscription"
                                        data-url="{{ route('subscriptions.cancel', $subscription) }}"
                                        data-name="{{ $subscription->member->full_name }}">
                                  <i class="fe fe-x-circle mr-1"></i> إلغاء الاشتراك
                                </button>
                              @endif

                              <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST"
                                    class="d-inline delete-subscription-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="dropdown-item text-danger btn-delete-subscription"
                                        data-name="{{ $subscription->member->full_name }}">
                                  <i class="fe fe-trash mr-1"></i> حذف
                                </button>
                              </form>
                            </div>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                          <i class="fe fe-repeat d-block mb-2" style="font-size: 28px;"></i>
                          لا توجد اشتراكات حتى الآن
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <nav aria-label="Table Paging" class="mt-3 text-muted">
                {{ $subscriptions->links() }}
              </nav>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="freezeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="freezeForm" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">تجميد الاشتراك</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p class="mb-3">
            العضو: <strong id="freezeMemberName"></strong><br>
            الاشتراك ينتهي: <strong id="freezeEndDate"></strong>
          </p>

          <div class="mb-3">
            <label class="form-label">سبب التجميد (اختياري)</label>
            <textarea class="form-control" name="reason" rows="3"
                      placeholder="مثال: سفر، إصابة، ظروف شخصية..."></textarea>
          </div>

          <div class="alert alert-warning mb-0 py-2 px-3" style="font-size:.85rem;">
            ⚠️ سيتم تمديد تاريخ انتهاء الاشتراك بعدد أيام التجميد عند إلغاء التجميد.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-warning">تجميد الاشتراك</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
  .subscriptions-table td,
  .subscriptions-table th {
    vertical-align: middle;
    padding: .9rem .75rem;
  }

  .subscriptions-table thead th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
    border-bottom: 1px solid #edf0f5;
  }

  .subscriptions-table tbody tr {
    background: #fff;
    border-bottom: 1px solid #f1f3f8;
  }

  .subscriptions-table tbody tr:last-child {
    border-bottom: none;
  }

  .subscriptions-table tbody tr:hover {
    background: #f6f9ff;
  }

  .price-tag {
    font-weight: 600;
  }

  .badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: .4rem .75rem;
    font-weight: 500;
    font-size: .78rem;
  }

  .badge-status-active    { background-color: rgba(25, 135, 84, .12);  color: #198754; }
  .badge-status-frozen    { background-color: rgba(255, 193, 7, .15);  color: #997404; }
  .badge-status-expired   { background-color: rgba(220, 53, 69, .12);  color: #dc3545; }
  .badge-status-cancelled { background-color: rgba(33, 37, 41, .1);    color: #212529; }

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
  document.querySelectorAll('.btn-freeze-subscription').forEach(function (btn) {
  btn.addEventListener('click', function () {
    document.getElementById('freezeMemberName').textContent = btn.getAttribute('data-name');
    document.getElementById('freezeEndDate').textContent = btn.getAttribute('data-end-date');
    document.getElementById('freezeForm').action = btn.getAttribute('data-url');
    $('#freezeModal').modal('show');
    });
  });
  document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-delete-subscription').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const form = btn.closest('form');
        const name = btn.getAttribute('data-name');

        Swal.fire({
          title: 'هل أنت متأكد؟',
          html: 'سيتم حذف اشتراك <strong>' + name + '</strong> بشكل نهائي.',
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

    document.querySelectorAll('.btn-cancel-subscription').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const url  = btn.getAttribute('data-url');
        const name = btn.getAttribute('data-name');

        Swal.fire({
          title: 'إلغاء الاشتراك؟',
          html: 'هيتم إلغاء اشتراك <strong>' + name + '</strong>. الإجراء ده مينفعش يترجع.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'نعم، ألغِ الاشتراك',
          cancelButtonText: 'تراجع',
          confirmButtonColor: '#dc3545',
          cancelButtonColor: '#6c757d',
          reverseButtons: true,
          focusCancel: true
        }).then(function (result) {
          if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '@csrf';
            document.body.appendChild(form);
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