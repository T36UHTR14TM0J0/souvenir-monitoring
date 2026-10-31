@props([
    'icon'        => 'fas fa-circle',
    'title'       => '',
    'subtitle'    => '',
    'iconVariant' => '', // '', 'warning', 'success', 'info'
])

@php
    $iconClass = 'page-title-icon';
    if ($iconVariant) {
        $iconClass .= ' page-title-icon-' . $iconVariant;
    }
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="page-title">
            <span class="{{ $iconClass }}">
                <i class="{{ $icon }}"></i>
            </span>
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="page-subtitle">{!! $subtitle !!}</p>
        @endif
    </div>
    @if(!$slot->isEmpty())
        <div>{{ $slot }}</div>
    @endif
</div>