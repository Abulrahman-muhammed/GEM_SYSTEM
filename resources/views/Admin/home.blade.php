@extends('admin.layouts.master')
@section('title')
    لوحة التحكم
@endsection
@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12">

      {{-- ══════════════════════════ الترحيب + الساعة الحية ══════════════════════════ --}}
      <div class="hero-banner mb-4">
        <div>
          <div class="hero-greeting" id="greetingText"></div>
          <h3 class="mb-1">{{ auth()->user()->name }} 👋</h3>
          <div class="text-muted">{{ now()->translatedFormat('l، d F Y') }}</div>
        </div>
        <div class="hero-clock" id="liveClock">--:--:-- --</div>
      </div>

      {{-- ══════════════════════════ التنبيهات ══════════════════════════ --}}
      @if (count($alerts))
        <div class="mb-4">
          @foreach ($alerts as $alert)
            <div class="alert-widget alert-widget-{{ $alert['type'] }}">
              <i class="fe {{ $alert['icon'] }}"></i>
              <span>{{ $alert['message'] }}</span>
            </div>
          @endforeach
        </div>
      @endif

      {{-- ══════════════════════════ الصف الأول: KPIs ══════════════════════════ --}}
      <div class="row mb-4">
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
          <div class="kpi-card kpi-blue">
            <div class="kpi-icon"><i class="fe fe-users"></i></div>
            <div class="kpi-value">{{ number_format($kpis['total_members']) }}</div>
            <div class="kpi-label">إجمالي الأعضاء</div>
          </div>
        </div>
        <div class="col-6 col-lg-3 mb-3 mb-lg-0">
          <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i class="fe fe-check-circle"></i></div>
            <div class="kpi-value">{{ number_format($kpis['today_attendance']) }}</div>
            <div class="kpi-label">الحضور اليوم</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="kpi-card kpi-green">
            <div class="kpi-icon"><i class="fe fe-dollar-sign"></i></div>
            <div class="kpi-value">{{ number_format($kpis['today_revenue'], 0) }} <small>جنيه</small></div>
            <div class="kpi-label">الإيراد اليوم</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="kpi-card kpi-orange">
            <div class="kpi-icon"><i class="fe fe-clock"></i></div>
            <div class="kpi-value">{{ number_format($kpis['expiring_subscriptions']) }}</div>
            <div class="kpi-label">اشتراكات هتنتهي قريب</div>
          </div>
        </div>
      </div>

      {{-- ══════════════════════════ الصف الثاني: الشارتس ══════════════════════════ --}}
      <div class="row mb-4">
        <div class="col-lg-8 mb-3 mb-lg-0">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
              <strong>الحضور — آخر 7 أيام</strong>
            </div>
            <div class="card-body">
              <div id="attendanceChart"></div>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white">
              <strong>توزيع الاشتراكات</strong>
            </div>
            <div class="card-body">
              <div id="subscriptionsChart"></div>
            </div>
          </div>
        </div>
      </div>

      {{-- ══════════════════════════ الصف الثالث: أحدث حضور + اشتراكات هتنتهي ══════════════════════════ --}}
      <div class="row mb-4">
        <div class="col-lg-6 mb-3 mb-lg-0">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <strong>أحدث حضور</strong>
              <a href="{{ route('attendances.index') }}" class="small text-muted">عرض الكل</a>
            </div>
            <div class="card-body p-0">
              @forelse ($recentAttendance as $attendance)
                <div class="list-row d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <img src="{{ $attendance->member->photo_url }}" class="list-avatar rounded-circle me-2" alt="">
                    <span class="font-weight-bold">{{ $attendance->member->full_name }}</span>
                  </div>
                  <span class="text-muted small">{{ $attendance->check_in->format('h:i A') }}</span>
                </div>
              @empty
                <div class="text-center text-muted py-4">لا يوجد حضور مسجل النهاردة</div>
              @endforelse
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <strong>اشتراكات هتنتهي قريب</strong>
              <a href="{{ route('subscriptions.index') }}" class="small text-muted">عرض الكل</a>
            </div>
            <div class="card-body p-0">
              @forelse ($expiringSoon as $subscription)
                @php $daysLeft = today()->diffInDays($subscription->end_date, false); @endphp
                <div class="list-row d-flex justify-content-between align-items-center">
                  <span class="font-weight-bold">{{ $subscription->member->full_name }}</span>
                  <span class="badge badge-pill {{ $daysLeft <= 1 ? 'badge-status-danger' : 'badge-status-warning' }}">
                    باقي {{ $daysLeft }} {{ $daysLeft == 1 ? 'يوم' : 'أيام' }}
                  </span>
                </div>
              @empty
                <div class="text-center text-muted py-4">مفيش اشتراكات هتنتهي قريب</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      {{-- ══════════════════════════ الصف الرابع: الإيرادات ══════════════════════════ --}}
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
              <strong>الإيرادات</strong>
            </div>
            <div class="card-body">
              <div class="row text-center">
                <div class="col-4">
                  <div class="revenue-value">{{ number_format($revenueStats['today'], 0) }}</div>
                  <div class="revenue-label">اليوم</div>
                </div>
                <div class="col-4">
                  <div class="revenue-value">{{ number_format($revenueStats['week'], 0) }}</div>
                  <div class="revenue-label">الأسبوع</div>
                </div>
                <div class="col-4">
                  <div class="revenue-value">{{ number_format($revenueStats['month'], 0) }}</div>
                  <div class="revenue-label">الشهر</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ══════════════════════════ الصف الخامس: آخر المدفوعات ══════════════════════════ --}}
      <div class="row mb-4">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <strong>آخر المدفوعات</strong>
              <a href="{{ route('payments.index') }}" class="small text-muted">عرض الكل</a>
            </div>
            <div class="card-body p-0">
              @forelse ($recentPayments as $payment)
                <div class="list-row d-flex justify-content-between align-items-center">
                  <span class="font-weight-bold">{{ $payment->subscription->member->full_name }}</span>
                  <span class="text-success font-weight-bold">{{ number_format($payment->amount, 2) }} جنيه</span>
                </div>
              @empty
                <div class="text-center text-muted py-4">لا توجد مدفوعات مسجلة</div>
              @endforelse
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
  .hero-banner {
    background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
    color: #fff;
    border-radius: 1rem;
    padding: 1.75rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .hero-greeting { font-size: .95rem; opacity: .85; margin-bottom: 4px; }
  .hero-banner h3 { color: #fff; }
  .hero-banner .text-muted { color: rgba(255,255,255,.75) !important; }

  .hero-clock {
    font-size: 2rem;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
    direction: ltr;
  }

  .alert-widget {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: .8rem 1.1rem;
    border-radius: .6rem;
    margin-bottom: .5rem;
    font-weight: 500;
  }

  .alert-widget-warning { background: rgba(255, 152, 0, .1); color: #b25e00; }
  .alert-widget-info    { background: rgba(13, 110, 253, .08); color: #0d6efd; }
  .alert-widget-danger  { background: rgba(220, 53, 69, .1); color: #dc3545; }

  .kpi-card {
    background: #fff;
    border-radius: .9rem;
    padding: 1.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
    height: 100%;
  }

  .kpi-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: .75rem;
  }

  .kpi-blue .kpi-icon   { background: rgba(13, 110, 253, .1);  color: #0d6efd; }
  .kpi-green .kpi-icon  { background: rgba(25, 135, 84, .12);  color: #198754; }
  .kpi-orange .kpi-icon { background: rgba(255, 152, 0, .12);  color: #e08600; }
  .kpi-red .kpi-icon    { background: rgba(220, 53, 69, .12);  color: #dc3545; }

  .kpi-value { font-size: 1.6rem; font-weight: 700; }
  .kpi-value small { font-size: .9rem; font-weight: 500; color: #6b7280; }
  .kpi-label { color: #6b7280; font-size: .82rem; }

  .card { border-radius: .9rem; overflow: hidden; }

  .list-row {
    padding: .8rem 1.25rem;
    border-bottom: 1px solid #f1f3f8;
  }
  .list-row:last-child { border-bottom: none; }

  .list-avatar { width: 32px; height: 32px; object-fit: cover; }

  .badge-status-warning { background: rgba(255, 152, 0, .12); color: #b25e00; padding: .35rem .7rem; }
  .badge-status-danger  { background: rgba(220, 53, 69, .12); color: #dc3545; padding: .35rem .7rem; }

  .revenue-value { font-size: 1.5rem; font-weight: 700; color: #198754; }
  .revenue-label { color: #6b7280; font-size: .82rem; }
</style>
@endpush

@push('scripts')
<script>
  /* ══════════════ الساعة الحية + التحية ══════════════ */
  function updateClockAndGreeting() {
    const now = new Date();

    let h = now.getHours();
    const m = String(now.getMinutes()).padStart(2, '0');
    const s = String(now.getSeconds()).padStart(2, '0');
    const ampm = h >= 12 ? 'PM' : 'AM';
    h = h % 12 || 12;

    document.getElementById('liveClock').textContent =
      String(h).padStart(2, '0') + ':' + m + ':' + s + ' ' + ampm;

    const hour = now.getHours();
    let greeting = 'مساء الخير';
    if (hour < 12) greeting = 'صباح الخير';
    else if (hour < 17) greeting = 'مساء الخير';

    document.getElementById('greetingText').textContent = greeting;
  }

  updateClockAndGreeting();
  setInterval(updateClockAndGreeting, 1000);

  /* ══════════════ شارت الحضور آخر 7 أيام ══════════════ */
  if (window.ApexCharts) {
    new ApexCharts(document.querySelector('#attendanceChart'), {
      chart: { type: 'bar', height: 260, toolbar: { show: false } },
      series: [{ name: 'الحضور', data: @json($attendanceLast7Days['data']) }],
      xaxis: { categories: @json($attendanceLast7Days['labels']) },
      colors: ['#0d6efd'],
      plotOptions: { bar: { borderRadius: 6, columnWidth: '45%' } },
      dataLabels: { enabled: false },
    }).render();

    /* ══════════════ شارت توزيع الاشتراكات ══════════════ */
    new ApexCharts(document.querySelector('#subscriptionsChart'), {
      chart: { type: 'donut', height: 260 },
      series: [
        {{ $subscriptionsBreakdown['counts']['active'] }},
        {{ $subscriptionsBreakdown['counts']['expired'] }},
        {{ $subscriptionsBreakdown['counts']['frozen'] }},
        {{ $subscriptionsBreakdown['counts']['cancelled'] }}
      ],
      labels: ['نشط', 'منتهي', 'مجمد', 'ملغي'],
      colors: ['#198754', '#dc3545', '#e08600', '#6c757d'],
      legend: { position: 'bottom' },
    }).render();
  }
</script>
@endpush