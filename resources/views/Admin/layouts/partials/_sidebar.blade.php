@php
    // كل Group هنا زي "Components" / "Apps" / "Extra" في القالب الأصلي
    // لو الـ heading فاضي، هيتعرض من غير عنوان فوقه (زي قسم الرئيسية)
    $menuGroups = [
        [
            'heading' => null,
            'items' => [
                ['title' => 'الرئيسية', 'icon' => 'fe-home', 'route' => 'dashboard'],
            ],
        ],

        [
            'heading' => 'إدارة الأعضاء',
            'items' => [
                [
                    'title' => 'الأعضاء',
                    'icon'  => 'fe-users',
                    'children' => [
                        ['title' => 'جميع الأعضاء', 'route' => 'members.index'],
                        ['title' => 'إضافة عضو',    'route' => 'members.create'],
                        ['title' => 'الاشتراكات المنتهية', 'route' => 'dashboard',  'badge' => 8,  'badge_color' => 'danger'],
                        ['title' => 'قربت تنتهي',          'route' => 'dashboard', 'badge' => 12, 'badge_color' => 'warning'],
                    ],
                ],
                [
                    'title' => 'الاشتراكات',
                    'icon'  => 'fe-credit-card',
                    'children' => [
                        ['title' => 'جميع الاشتراكات', 'route' => 'subscriptions.index'],
                        ['title' => 'إضافة اشتراك',    'route' => 'subscriptions.create'],


 
                    ],
                ],
                [
                    'title' => 'الباقات',
                    'icon'  => 'fe-box',
                    'children' => [
                        ['title' => 'جميع الباقات', 'route' => 'plans.index'],
                        ['title' => 'إضافة باقة',   'route' => 'plans.create'],
                    ],
                ],
                [
                    'title' => 'العروض',
                    'icon'  => 'fe-gift',
                    'children' => [
                        ['title' => 'جميع العروض', 'route' => 'offers.index'],
                        ['title' => 'إضافة عرض',   'route' => 'offers.create'],
                    ],
                ],
            ],
        ],

        [
            'heading' => 'العمليات اليومية',
            'items' => [
                [
                    'title' => 'الحضور والانصراف',
                    'icon'  => 'fe-calendar',
                    'children' => [
                        ['title' => 'تسجيل حضور', 'route' => 'attendances.scan'],
                        ['title' => 'سجل الحضور', 'route' => 'attendances.index'],
                    ],
                ],
                [
                    'title' => 'المدفوعات',
                    'icon'  => 'fe-dollar-sign',
                    'children' => [
                        ['title' => 'جميع المدفوعات', 'route' => 'payments.index'],
                        ['title' => 'إضافة دفعة',     'route' => 'payments.create'],
                    ],
                ],
                [
                    'title' => 'المدربون',
                    'icon'  => 'fe-users',
                    'children' => [
                        ['title' => 'جميع المدربين', 'route' => 'dashboard'],
                        ['title' => 'إضافة مدرب',    'route' => 'dashboard'],
                    ],
                ],
            ],
        ],

        [
            'heading' => 'التقارير والإعدادات',
            'items' => [
                [
                    'title' => 'التقارير',
                    'icon'  => 'fe-bar-chart-2',
                    'children' => [
                        ['title' => 'جميع التقارير',  'route' => 'reports.index'],
                    ],
                ],
                [
                    'title' => 'الإعدادات',
                    'icon'  => 'fe-settings',
                    'children' => [
                        ['title' => 'بيانات الجيم',    'route' => 'dashboard'],
                        ['title' => 'المستخدمون',      'route' => 'dashboard'],
                        ['title' => 'النسخ الاحتياطي', 'route' => 'dashboard'],
                    ],
                ],
            ],
        ],
    ];
@endphp

<a href="{{ route('dashboard') }}" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
    <i class="fe fe-x"><span class="sr-only"></span></i>
</a>

<nav class="vertnav navbar navbar-light">
    {{-- اللوجو الأصلي زي ما هو من غير أي تعديل --}}
    <div class="w-100 mb-4 d-flex">
        <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('dashboard') }}">
            <svg version="1.1" id="logo" class="navbar-brand-img brand-sm" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 120 120" xml:space="preserve">
                <g>
                    <polygon class="st0" points="78,105 15,105 24,87 87,87 	" />
                    <polygon class="st0" points="96,69 33,69 42,51 105,51 	" />
                    <polygon class="st0" points="78,33 15,33 24,15 87,15 	" />
                </g>
            </svg>
        </a>
    </div>

    {{-- لف على كل Group، ولو ليه عنوان اعرضه زي نفس شكل القالب الأصلي --}}
    @foreach ($menuGroups as $group)

        @if ($group['heading'])
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>{{ $group['heading'] }}</span>
            </p>
        @endif

        <ul class="navbar-nav flex-fill w-100 mb-2">
            @foreach ($group['items'] as $index => $item)
                @php $hasChildren = !empty($item['children']); @endphp

                <li class="nav-item {{ $hasChildren ? 'dropdown' : 'w-100' }}">
                    @if ($hasChildren)
                        <a href="#menu-{{ Str::slug($item['title']) }}" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                            <i class="fe {{ $item['icon'] }} fe-16"></i>
                            <span class="ml-3 item-text">{{ $item['title'] }}</span>
                        </a>

                        <ul class="collapse list-unstyled pl-4 w-100" id="menu-{{ Str::slug($item['title']) }}">
                            @foreach ($item['children'] as $child)
                                <li class="nav-item">
                                    <a class="nav-link pl-3 d-flex align-items-center justify-content-between" href="{{ route($child['route']) }}">
                                        <span class="ml-1 item-text">{{ $child['title'] }}</span>

                                        @if (!empty($child['badge']))
                                            <span class="badge badge-pill badge-{{ $child['badge_color'] ?? 'primary' }}">
                                                {{ $child['badge'] }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a class="nav-link" href="{{ route($item['route']) }}">
                            <i class="fe {{ $item['icon'] }} fe-16"></i>
                            <span class="ml-3 item-text">{{ $item['title'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endforeach
</nav>