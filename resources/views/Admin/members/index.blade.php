@extends('admin.layouts.master')
@section('title', 'الأعضاء')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12 my-4">

        <x-alert />


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

                <x-search-toolbar
                    :action="route('members.index')"
                    placeholder="ابحث بالاسم أو الهاتف" />
                
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
                      <th class="text-center ">الإجراءات</th>
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
                            @if (!$member->activeSubscription()->exists() && $member->status)
                              <a href="{{ route('subscriptions.create', ['member' => $member->id]) }}"
                                class="btn btn-sm btn-icon btn-outline-primary" title="إنشاء اشتراك" data-toggle="tooltip">
                                  <i class="fe fe-plus-circle  me-2"></i>
                              </a>
                            @endif
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
<link rel="stylesheet" href="{{ asset('css/custom/member-index.css') }}">
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