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

        @if($errors->any() && !session('swal'))
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                html: `
                    <div class="swal-error-list">
                        <p>Silakan perbaiki kesalahan berikut:</p>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                `,
                confirmButtonText: '<i class="fas fa-check me-1"></i> OK, Saya Perbaiki',
                background: '#ffffff',
                width: '500px',
            }).then((result) => {
                if (result.isConfirmed) {
                    const firstInvalid = document.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        @endif
    });
</script>