{{-- ============ FLASH MESSAGES — SweetAlert2 ============ --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('swal'))
            Swal.fire({
                icon: @json(session('swal.type')),
                title: @json(session('swal.title')),
                text: @json(session('swal.text')),
                confirmButtonText: 'OK',
                background: '#ffffff',
                timer: @json(session('swal.type')) === 'success' ? 2500 : undefined,
                timerProgressBar: @json(session('swal.type')) === 'success',
                showConfirmButton: @json(session('swal.type')) !== 'success',
            });
        @endif

        @if(session('success') && !session('swal'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                background: '#ffffff',
                timer: 2500,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        @endif

        @if(session('error') && !session('swal'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                background: '#ffffff',
            });
        @endif
    });
</script>
