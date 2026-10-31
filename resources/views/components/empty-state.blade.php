@props([
    'icon'    => 'fas fa-inbox',
    'title'   => 'Tidak Ada Data',
    'message' => 'Belum ada data untuk ditampilkan.',
])

<div class="text-center py-5">
    <div class="empty-icon mb-3">
        <i class="{{ $icon }} fs-3"></i>
    </div>
    <h6 class="fw-semibold mb-1 empty-state-title">{{ $title }}</h6>
    <p class="small mb-0 empty-state-message">{{ $message }}</p>
</div>