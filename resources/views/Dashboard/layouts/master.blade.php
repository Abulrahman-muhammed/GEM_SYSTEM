<!DOCTYPE html>
<html lang="en">

<head>
	@include('Dashboard.layouts.partials._head')
</head>

<body>

    <!--==================== Preloader Start ====================-->
    <div class="loader-mask">
        <div class="loader">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--==================== Preloader End ====================-->

    <!--==================== Overlay Start ====================-->
    <div class="overlay"></div>
    <!--==================== Overlay End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- ==================== Scroll to Top End Here ==================== -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- ==================== Scroll to Top End Here ==================== -->

    <!-- ==================== Mobile Menu Start Here ==================== -->
	@include('Dashboard.layouts.partials._mobile-menu')
    <!-- ==================== Mobile Menu End Here ==================== -->

    <!-- ================================== Dashboard Start =========================== -->
    <section class="dashboard">
        <div class="dashboard__inner d-flex">

            <!-- ===================== Dashboard Sidebar Start ======================= -->
			@include('Dashboard.layouts.partials._sidebar')
            <!-- ===================== Dashboard Sidebar End ======================= -->

            <div class="dashboard-body">

                <!-- Dashboard Nav Start -->
				@include('Dashboard.layouts.partials._nav')
                <!-- Dashboard Nav End -->


                <div class="dashboard-body__content">
                    <!-- start content -->
                    @yield('content')  
                    <!-- end content -->
                </div>

                <!-- ====================== Dashboard Footer Start ======================== -->
                @include('Dashboard.layouts.partials._footer')
                <!-- ====================== Dashboard Footer End ======================== -->


            </div>
        </div>
    </section>
    <!-- ================================== Dashboard End =========================== -->

    @include('Dashboard.layouts.partials._scripts')


</body>

</html>