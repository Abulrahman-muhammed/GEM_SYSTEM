@extends('admin.layouts.master')
@section('title', 'العروض')
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
                  <i class="fe fe-tag"></i>
                </div>
                <div>
                  <h5 class="mb-0">العروض</h5>
                  <small class="text-muted">يوجد {{ $offers->total() }} عرض مسجل</small>
                </div>
              </div>
              <a href="{{ route('offers.create') }}" class="btn btn-primary">
                <i class="fe fe-plus mr-1"></i> إضافة عرض
              </a>
            </div>

            <div class="card-body">
              <!-- Toolbar -->
              <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="text-muted mb-2 mb-md-0">
                  إجمالي العروض: <strong>{{ $offers->total() }}</strong>
                </div>

                <x-search-toolbar :action="route('offers.index')" placeholder="ابحث باسم العرض" />

              </div>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-hover mb-0 offers-table">
                  <thead>
                    <tr>
                      <th>اسم العرض</th>
                      <th>نوع الخصم</th>
                      <th>قيمة الخصم</th>
                      <th>تاريخ البداية</th>
                      <th>تاريخ النهاية</th>
                      <th>الحالة</th>
                      <th class="text-center">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($offers as $offer)
                      <tr>
                        <td>
                          <div>
                            <h6 class="mb-0">{{ $offer->name }}</h6>
                            <small class="text-muted">عرض #{{ $offer->id }}</small>
                          </div>
                        </td>
                        <td class="text-muted">
                          {{ $offer->discount_type->label() }}
                        </td>
                        <td>
                          <span class="price-tag">
                            {{ rtrim(rtrim(number_format($offer->discount_value, 2), '0'), '.') }}{{ $offer->discount_type->value === 'percentage' ? '%' : ' جنيه' }}
                          </span>
                        </td>
                        <td class="text-muted">{{ $offer->start_date->format('Y-m-d') }}</td>
                        <td class="text-muted">{{ $offer->end_date->format('Y-m-d') }}</td>
                        <td>
                          @if ($offer->status)
                            <span class="badge badge-pill badge-status badge-status-active">
                              <i class="fe fe-check-circle"></i> نشط
                            </span>
                          @else
                            <span class="badge badge-pill badge-status badge-status-inactive">
                              <i class="fe fe-x-circle"></i> غير نشط
                            </span>
                          @endif
                        </td>
                        <td>
                          <div class="btn-group justify-content-center d-flex">
                            <a href="{{ route('offers.edit', $offer) }}"
                               class="btn btn-sm btn-icon btn-outline-primary" title="تعديل" data-toggle="tooltip">
                              <i class="fe fe-edit"></i>
                            </a>
                            <form action="{{ route('offers.destroy', $offer) }}" method="POST"
                                  class="d-inline delete-offer-form">
                              @csrf
                              @method('DELETE')
                              <button type="button"
                                      class="btn btn-sm btn-icon btn-outline-danger btn-delete-offer"
                                      data-name="{{ $offer->name }}"
                                      title="حذف" data-toggle="tooltip">
                                <i class="fe fe-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                          <i class="fe fe-tag d-block mb-2" style="font-size: 28px;"></i>
                          لا توجد عروض حتى الآن
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <nav aria-label="Table Paging" class="mt-3 text-muted">
                {{ $offers->links() }}
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
  .offers-table td,
  .offers-table th {
    vertical-align: middle;
    padding: .9rem .75rem;
  }

  .offers-table thead th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
    border-bottom: 1px solid #edf0f5;
  }

  .offers-table tbody tr {
    background: #fff;
    border-bottom: 1px solid #f1f3f8;
    transition: background-color .15s ease;
  }

  .offers-table tbody tr:last-child {
    border-bottom: none;
  }

  .offers-table tbody tr:hover {
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
    document.querySelectorAll('.btn-delete-offer').forEach(function (btn) {
      btn.addEventListener('click', function () {
        const form = btn.closest('form');
        const offerName = btn.getAttribute('data-name');

        Swal.fire({
          title: 'هل أنت متأكد؟',
          html: 'سيتم حذف عرض <strong>' + offerName + '</strong> بشكل نهائي.',
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