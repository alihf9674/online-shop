@if(session('swal-error'))

    <script>
        $(document).ready(() => {
            Swal.fire({
                title: 'خطا!',
                text: '{{ session('swal-error') }}',
                icon: 'error',
                confirmButtonText: 'باشه',
            });
        });
    </script>

@endif
