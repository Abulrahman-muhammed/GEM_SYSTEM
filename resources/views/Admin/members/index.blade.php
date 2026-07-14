@extends('admin.layouts.master')
@section('title', 'الأعضاء')
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
                  <i class="fe fe-users"></i>
                </div>
                <div>
                  <h5 class="mb-0">جميع الأعضاء</h5>
                  <small class="text-muted">يوجد {{ $members->total() }} عضو مسجل</small>
                </div>
              </div>
              <a href="{{ route('members.create') }}" class="btn btn-primary">
                <i class="fe fe-plus mr-1"></i> إضافة عضو
              </a>
            </div>

            <div class="card-body">
              <!-- Toolbar -->
              <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div class="text-muted mb-2 mb-md-0">
                  إجمالي الأعضاء: <strong>{{ $members->total() }}</strong>
                </div>

                <form class="d-flex align-items-center" method="GET" action="{{ route('members.index') }}">
                  <div class="input-group input-group-toolbar">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-white border-right-0">
                        <i class="fe fe-search"></i>
                      </span>
                    </div>
                    <input type="text" name="search" class="form-control border-left-0" id="search"
                           value="{{ request('search') }}" placeholder="ابحث بالاسم أو الهاتف">
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
                <table class="table table-hover mb-0 members-table">
                  <thead>
                    <tr>
                      <th>الصورة</th>
                      <th>الاسم</th>
                      <th>رقم الهاتف</th>
                      <th>النوع</th>
                      <th>العمر</th>
                      <th class="w-25">العنوان</th>
                      <th>الحالة</th>
                      <th class="text-center">الإجراءات</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($members as $member)
                      <tr>
                        <td>
                          <div class="avatar avatar-lg">
                            <img src="{{ $member->photo ? $member->photo_url : asset('assets/avatars/face-1.jpg') }}"
                                 alt="{{ $member->full_name }}" class="avatar-img rounded-circle">
                          </div>
                        </td>
                        <td>
                          <div>
                            <h6 class="mb-0">{{ $member->full_name }}</h6>
                            <small class="text-muted">عضو #{{ $member->id }}</small>
                          </div>
                        </td>
                        <td>
                          <a href="tel:{{ $member->phone }}" class="phone-link">
                            <i class="fe fe-phone"></i> {{ $member->phone }}
                          </a>
                        </td>
                        <td class="text-muted">
                          {{ $member->gender->label() }}
                        </td>
                        <td class="text-muted">
                          {{ $member->age ?? '—' }} سنة
                        </td>
                        <td class="w-25">
                          <small class="text-muted">{{ $member->address ?? '—' }}</small>
                        </td>
                        <td>
                          @if ($member->status)
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
                            <a href="{{ route('members.edit', $member) }}"
                               class="btn btn-sm btn-icon btn-outline-primary" title="تعديل" data-toggle="tooltip">
                              <i class="fe fe-edit"></i>
                            </a>
                            <a href="{{ route('members.show', $member) }}"
                               class="btn btn-sm btn-icon btn-outline-primary" title="عرض" data-toggle="tooltip">
                              <i class="fe fe-eye"></i>
                            </a>

                            <form action="{{ route('members.destroy', $member) }}" method="POST"
                                  class="d-inline delete-member-form">
                              @csrf
                              @method('DELETE')
                              <button type="button"
                                      class="btn btn-sm btn-icon btn-outline-danger btn-delete-member"
                                      data-name="{{ $member->full_name }}"
                                      title="حذف" data-toggle="tooltip">
                                <i class="fe fe-trash"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                          <i class="fe fe-users d-block mb-2" style="font-size: 28px;"></i>
                          لا يوجد أعضاء حتى الآن
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <nav aria-label="Table Paging" class="mt-3 text-muted">
                {{ $members->links() }}
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
  .members-table td,
  .members-table th {
    vertical-align: middle;
    padding: .9rem .75rem;
  }

  .members-table thead th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
    border-bottom: 1px solid #edf0f5;
  }

  .members-table tbody tr {
    background: #fff;
    border-bottom: 1px solid #f1f3f8;
    transition: background-color .15s ease, transform .15s ease;
  }

  .members-table tbody tr:last-child {
    border-bottom: none;
  }

  .members-table tbody tr:hover {
    background: #f6f9ff;
  }

  .avatar-img {
    width: 46px;
    height: 46px;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #eef0f4;
  }

  .phone-link {
    color: #4b5563;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .9rem;
  }

  .phone-link:hover {
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