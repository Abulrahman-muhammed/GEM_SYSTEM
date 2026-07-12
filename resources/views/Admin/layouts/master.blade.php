<!doctype html>
<html lang="en">
  <head>
    @include('Admin.layouts.partials._head')
  </head>
  <body class="vertical  light rtl ">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        @include('Admin.layouts.partials._navbar')
      </nav>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        @include('Admin.layouts.partials._sidebar')
      </aside>
      <main role="main" class="main-content">
        @yield('content')
      </main> <!-- main -->
    </div> <!-- .wrapper -->
    @include('Admin.layouts.partials._scripts')
  </body>
</html>