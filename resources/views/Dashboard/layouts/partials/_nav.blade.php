<div class="dashboard-nav bg-white flx-between gap-md-3 gap-2">
    <div class="dashboard-nav__left flx-align gap-md-3 gap-2">
        <button type="button" class="icon-btn bar-icon text-heading bg-gray-seven flx-center">
            <i class="las la-bars"></i>
        </button>
        <button type="button" class="icon-btn arrow-icon text-heading bg-gray-seven flx-center">
            <img src="{{asset('assets')}}/images/icons/angle-right.svg" alt="">
        </button>
        <form action="#" class="search-input d-sm-block d-none">
            <span class="icon">
                <img src="{{asset('assets')}}/images/icons/search-dark.svg" alt="" class="white-version">
                <img src="{{asset('assets')}}/images/icons/search-dark-white.svg" alt="" class="dark-version">
            </span>
            <input type="text" class="common-input common-input--md common-input--bg pill w-100"
                placeholder="Search here...">
        </form>
    </div>
    <div class="dashboard-nav__right">
        <div class="header-right flx-align">
            <div class="header-right__inner gap-sm-3 gap-2 flx-align d-flex">

                <div class="user-profile">
                    <button class="user-profile__button flex-align">
                        <span class="user-profile__thumb">
                            <img src="{{asset('assets')}}/images/thumbs/user-profile.png" class="cover-img" alt="">
                        </span>
                    </button>
                    <ul class="user-profile-dropdown">
                        <li class="sidebar-list__item">
                            <a href="dashboard-profile.html" class="sidebar-list__link">
                                <span class="sidebar-list__icon">
                                    <img src="{{asset('assets')}}/images/icons/sidebar-icon2.svg" alt=""
                                        class="icon">
                                    <img src="{{asset('assets')}}/images/icons/sidebar-icon-active2.svg" alt=""
                                        class="icon icon-active">
                                </span>
                                <span class="text">Profile</span>
                            </a>
                        </li>

                        <li class="sidebar-list__item">
                            <a href="setting.html" class="sidebar-list__link">
                                <span class="sidebar-list__icon">
                                    <img src="{{asset('assets')}}/images/icons/sidebar-icon10.svg" alt=""
                                        class="icon">
                                    <img src="{{asset('assets')}}/images/icons/sidebar-icon-active10.svg" alt=""
                                        class="icon icon-active">
                                </span>
                                <span class="text">Settings</span>
                            </a>
                        </li>
                        <li class="sidebar-list__item">
                            <a href="{{ route('logout') }}"
                            class="sidebar-list__link"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                                <span class="sidebar-list__icon">
                                    <img src="{{ asset('assets/images/icons/sidebar-icon13.svg') }}" alt="" class="icon">
                                    <img src="{{ asset('assets/images/icons/sidebar-icon-active13.svg') }}" alt="" class="icon icon-active">
                                </span>

                                <span class="text">Logout</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
