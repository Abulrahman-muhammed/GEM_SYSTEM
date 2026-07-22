@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'تم بنجاح',
            text: @json(session('success')),
            timer: 2500,
            showConfirmButton: false,
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'حدث خطأ',
            text: @json(session('error')),
        });
    </script>
@endif

@if (session('warning'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: @json(session('warning')),
        });
    </script>
@endif

@if (session('info'))
    <script>
        Swal.fire({
            icon: 'info',
            title: 'معلومة',
            text: @json(session('info')),
        });
    </script>
@endif