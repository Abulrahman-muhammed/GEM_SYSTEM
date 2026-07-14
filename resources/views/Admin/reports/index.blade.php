@extends('admin.layouts.master')
@section('title', 'التقارير')
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="row">
        <div class="col-md-12 my-4">

          <h4 class="mb-4"><i class="fe fe-bar-chart-2 me-2"></i> التقارير</h4>

          {{-- Tabs Nav --}}
          <ul class="nav nav-tabs report-tabs mb-4" id="reportTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="members-tab" data-toggle="tab" href="#membersPane" role="tab">
                <i class="fe fe-users mr-1"></i> الأعضاء
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendancePane" role="tab">
                <i class="fe fe-clock mr-1"></i> الحضور
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="offers-tab" data-toggle="tab" href="#offersPane" role="tab">
                <i class="fe fe-tag mr-1"></i> العروض
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="revenue-tab" data-toggle="tab" href="#revenuePane" role="tab">
                <i class="fe fe-dollar-sign mr-1"></i> الإيرادات
              </a>
            </li>
          </ul>

          <div class="tab-content">

            {{-- ══════════════════════════ 1) تقرير الأعضاء ══════════════════════════ --}}
            <div class="tab-pane fade show active" id="membersPane" role="tabpanel">

              <div class="row mb-4">
                <div class="col-6 col-lg-3 mb-3">
                  <div class="kpi-card kpi-blue">
                    <div class="kpi-value">{{ number_format($membersReport['total_members']) }}</div>
                    <div class="kpi-label">إجمالي الأعضاء</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                  <div class="kpi-card kpi-green">
                    <div class="kpi-value">{{ number_format($membersReport['active_members']) }}</div>
                    <div class="kpi-label">النشطين</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="kpi-card kpi-gray">
                    <div class="kpi-value">{{ number_format($membersReport['inactive_members']) }}</div>
                    <div class="kpi-label">غير النشطين</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="kpi-card kpi-orange">
                    <div class="kpi-value">{{ number_format($membersReport['new_this_month']) }}</div>
                    <div class="kpi-label">أعضاء جدد هذا الشهر</div>
                  </div>
                </div>
              </div>

              <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap py-3">
                  <form method="GET" class="d-flex align-items-center mb-2 mb-md-0">
                    <input type="hidden" name="tab" value="members">
                    <div class="input-group input-group-toolbar">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-white border-right-0"><i class="fe fe-search"></i></span>
                      </div>
                      <input type="text" name="members_search" class="form-control border-left-0"
                             value="{{ request('members_search') }}" placeholder="ابحث بالاسم أو الهاتف">
                    </div>
                  </form>
                  <div class="d-flex gap-2">
                    <a href="{{ route('reports.members.export.excel', request()->only('members_search')) }}"
                       class="btn btn-outline-success btn-sm">
                      <i class="fe fe-file-text mr-1"></i> Excel
                    </a>
                    <a href="{{ route('reports.members.export.pdf', request()->only('members_search')) }}"
                       class="btn btn-outline-danger btn-sm">
                      <i class="fe fe-file mr-1"></i> PDF
                    </a>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table table-hover mb-0 report-table">
                    <thead>
                      <tr>
                        <th>الاسم</th>
                        <th>الهاتف</th>
                        <th>الحالة</th>
                        <th>تاريخ التسجيل</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($membersReport['members'] as $member)
                        <tr>
                          <td>{{ $member->full_name }}</td>
                          <td class="text-muted">{{ $member->phone }}</td>
                          <td>
                            @if ($member->status)
                              <span class="badge badge-pill badge-status badge-status-active">نشط</span>
                            @else
                              <span class="badge badge-pill badge-status badge-status-inactive">غير نشط</span>
                            @endif
                          </td>
                          <td class="text-muted">{{ $member->created_at->format('Y-m-d') }}</td>
                        </tr>
                      @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">لا يوجد أعضاء</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                <div class="p-3">{{ $membersReport['members']->links() }}</div>
              </div>
            </div>

            {{-- ══════════════════════════ 2) تقرير الحضور ══════════════════════════ --}}
            <div class="tab-pane fade" id="attendancePane" role="tabpanel">

              <div class="row mb-4">
                <div class="col-6 col-lg-3 mb-3">
                  <div class="kpi-card kpi-blue">
                    <div class="kpi-value">{{ number_format($attendanceReport['today_count']) }}</div>
                    <div class="kpi-label">حضور اليوم</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="kpi-card kpi-green">
                    <div class="kpi-value">{{ number_format($attendanceReport['week_count']) }}</div>
                    <div class="kpi-label">حضور هذا الأسبوع</div>
                  </div>
                </div>
              </div>

              <form method="GET" class="row g-2 align-items-end mb-4">
                <input type="hidden" name="tab" value="attendance">
                <div class="col-auto">
                  <label class="small text-muted d-block">من</label>
                  <input type="date" name="attendance_from" value="{{ $attendanceReport['from'] }}" class="form-control">
                </div>
                <div class="col-auto">
                  <label class="small text-muted d-block">إلى</label>
                  <input type="date" name="attendance_to" value="{{ $attendanceReport['to'] }}" class="form-control">
                </div>
                <div class="col-auto">
                  <button class="btn btn-primary"><i class="fe fe-filter mr-1"></i> فلترة</button>
                </div>
              </form>

              <div class="row mb-4">
                <div class="col-lg-6 mb-3 mb-lg-0">
                  <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white"><strong>أكثر الأعضاء حضورًا</strong></div>
                    <div class="card-body p-0">
                      @foreach ($attendanceReport['top_members'] as $member)
                        <div class="list-row d-flex justify-content-between">
                          <span>{{ $member->full_name }}</span>
                          <span class="badge badge-pill badge-status badge-status-active">{{ $member->visits_count }} زيارة</span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white"><strong>أقل الأعضاء حضورًا</strong></div>
                    <div class="card-body p-0">
                      @foreach ($attendanceReport['least_members'] as $member)
                        <div class="list-row d-flex justify-content-between">
                          <span>{{ $member->full_name }}</span>
                          <span class="badge badge-pill badge-status badge-status-inactive">{{ $member->visits_count }} زيارة</span>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>

              <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><strong>سجل الحضور بالفترة المحددة</strong></div>
                <div class="table-responsive">
                  <table class="table table-hover mb-0 report-table">
                    <thead>
                      <tr><th>العضو</th><th>التاريخ</th><th>وقت الحضور</th><th>وقت الانصراف</th></tr>
                    </thead>
                    <tbody>
                      @forelse ($attendanceReport['attendances'] as $attendance)
                        <tr>
                          <td>{{ $attendance->member->full_name }}</td>
                          <td class="text-muted">{{ $attendance->date->format('Y-m-d') }}</td>
                          <td class="text-muted">{{ $attendance->check_in->format('h:i A') }}</td>
                          <td class="text-muted">{{ $attendance->check_out?->format('h:i A') ?? '—' }}</td>
                        </tr>
                      @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">لا يوجد سجلات</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                <div class="p-3">{{ $attendanceReport['attendances']->links() }}</div>
              </div>
            </div>

            {{-- ══════════════════════════ 3) تقرير العروض ══════════════════════════ --}}
            <div class="tab-pane fade" id="offersPane" role="tabpanel">

              @if ($offersReport['best_selling'])
                <div class="alert-widget alert-widget-info mb-4">
                  <i class="fe fe-award"></i>
                  <span>أكثر عرض مبيعًا: <strong>{{ $offersReport['best_selling']->name }}</strong> ({{ $offersReport['best_selling']->subscriptions_count }} مشترك)</span>
                </div>
              @endif

              <div class="card shadow-sm border-0">
                <div class="card-header bg-white"><strong>أداء العروض</strong></div>
                <div class="table-responsive">
                  <table class="table table-hover mb-0 report-table">
                    <thead>
                      <tr>
                        <th>اسم العرض</th>
                        <th>عدد المشتركين</th>
                        <th>الإيراد الناتج</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($offersReport['offers'] as $offer)
                        <tr>
                          <td>{{ $offer->name }}</td>
                          <td class="text-muted">{{ $offer->subscriptions_count }}</td>
                          <td><span class="price-tag">{{ number_format($offer->revenue ?? 0, 2) }} جنيه</span></td>
                        </tr>
                      @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">لا توجد عروض</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            {{-- ══════════════════════════ 4) تقرير الإيرادات ══════════════════════════ --}}
            <div class="tab-pane fade" id="revenuePane" role="tabpanel">

              <form method="GET" class="d-flex flex-wrap gap-2 mb-4" id="revenueFilterForm">
                <input type="hidden" name="tab" value="revenue">
                <div class="btn-group" role="group">
                  @foreach (['today' => 'اليوم', 'week' => 'الأسبوع', 'month' => 'الشهر', 'year' => 'سنة', 'custom' => 'Custom Range'] as $value => $label)
                    <input type="radio" class="btn-check" name="revenue_period" id="period_{{ $value }}"
                           value="{{ $value }}" {{ $revenueReport['period'] === $value ? 'checked' : '' }}
                           onchange="this.form.submit()" autocomplete="off">
                    <label class="btn btn-outline-primary btn-sm" for="period_{{ $value }}">{{ $label }}</label>
                  @endforeach
                </div>

                @if ($revenueReport['period'] === 'custom')
                  <input type="date" name="revenue_from" value="{{ request('revenue_from', $revenueReport['from']) }}" class="form-control form-control-sm w-auto">
                  <input type="date" name="revenue_to" value="{{ request('revenue_to', $revenueReport['to']) }}" class="form-control form-control-sm w-auto">
                  <button class="btn btn-primary btn-sm">تطبيق</button>
                @endif
              </form>

              <div class="row">
                <div class="col-6 col-lg-3 mb-3">
                  <div class="kpi-card kpi-green">
                    <div class="kpi-value">{{ number_format($revenueReport['total_revenue'], 0) }} <small>جنيه</small></div>
                    <div class="kpi-label">إجمالي الإيرادات</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3 mb-3">
                  <div class="kpi-card kpi-blue">
                    <div class="kpi-value">{{ number_format($revenueReport['subscriptions_count']) }}</div>
                    <div class="kpi-label">عدد الاشتراكات</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="kpi-card kpi-red">
                    <div class="kpi-value">{{ number_format($revenueReport['late_count']) }}</div>
                    <div class="kpi-label">المتأخرين في الدفع</div>
                  </div>
                </div>
                <div class="col-6 col-lg-3">
                  <div class="kpi-card kpi-orange">
                    <div class="kpi-value">{{ number_format($revenueReport['discounts_total'], 0) }} <small>جنيه</small></div>
                    <div class="kpi-label">إجمالي الخصومات</div>
                  </div>
                </div>
              </div>

              <div class="text-muted small mt-2">
                الفترة: {{ $revenueReport['from'] }} — {{ $revenueReport['to'] }}
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
<style>
  .report-tabs .nav-link { border: none; color: #6b7280; font-weight: 500; padding: .75rem 1.25rem; }
  .report-tabs .nav-link.active { color: #0d6efd; border-bottom: 2px solid #0d6efd; background: transparent; }

  .kpi-card {
    background: #fff;
    border-radius: .9rem;
    padding: 1.1rem 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
  }
  .kpi-value { font-size: 1.4rem; font-weight: 700; }
  .kpi-value small { font-size: .85rem; font-weight: 500; color: #6b7280; }
  .kpi-label { color: #6b7280; font-size: .8rem; margin-top: 2px; }

  .kpi-blue   { border-right: 3px solid #0d6efd; }
  .kpi-green  { border-right: 3px solid #198754; }
  .kpi-orange { border-right: 3px solid #e08600; }
  .kpi-red    { border-right: 3px solid #dc3545; }
  .kpi-gray   { border-right: 3px solid #6c757d; }

  .card { border-radius: .9rem; overflow: hidden; }

  .report-table th {
    background: #f8f9fc;
    color: #6b7280;
    font-weight: 600;
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: .04em;
    border-top: none;
  }
  .report-table td, .report-table th { vertical-align: middle; padding: .8rem .9rem; }

  .list-row { padding: .75rem 1.25rem; border-bottom: 1px solid #f1f3f8; }
  .list-row:last-child { border-bottom: none; }

  .badge-status {
    display: inline-flex; align-items: center; gap: 5px;
    padding: .35rem .7rem; font-weight: 500; font-size: .75rem;
  }
  .badge-status-active   { background-color: rgba(25, 135, 84, .12); color: #198754; }
  .badge-status-inactive { background-color: rgba(108, 117, 125, .12); color: #6c757d; }

  .alert-widget {
    display: flex; align-items: center; gap: 10px;
    padding: .8rem 1.1rem; border-radius: .6rem; font-weight: 500;
  }
  .alert-widget-info { background: rgba(13, 110, 253, .08); color: #0d6efd; }

  .price-tag { font-weight: 600; color: #198754; }

  .input-group-toolbar .form-control { max-width: 260px; }

  .btn-check:checked + .btn-outline-primary {
    background: #0d6efd;
    color: #fff;
  }
</style>
@endpush

@push('scripts')
<script>
  // الحفاظ على الـ Tab المفتوح بعد أي Filter submit (عن طريق ?tab=xxx في الـ URL)
  document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get('tab');
    if (tab) {
      const trigger = document.querySelector('#' + tab + '-tab');
      if (trigger && window.jQuery) {
        jQuery(trigger).tab('show');
      } else if (trigger) {
        trigger.click();
      }
    }
  });
</script>
@endpush