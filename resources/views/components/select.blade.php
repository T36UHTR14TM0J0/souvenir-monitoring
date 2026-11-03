@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'options' => [],
    'selected' => null,
    'hint' => null,
])

@php
    $id = $id ?? $name;
    $selectedVal = old($name, $selected);
@endphp

<div class="mb-3">
    @if($label)
        <label class="form-label-custom" for="{{ $id }}">
            {{ $label }}
            @if($required)
                <span class="required">*</span>
            @endif
        </label>
    @endif

    <select name="{{ $name }}"
            id="{{ $id }}"
            {{ $attributes->merge(['class' => 'form-select form-select-modern ' . ($errors->has($name) ? 'is-invalid' : '')]) }}
            @if($placeholder) data-placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif>
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if(!empty($options))
            @foreach($options as $val => $text)
                <option value="{{ $val }}" {{ (string)$selectedVal === (string)$val ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if($hint && !$errors->has($name))
        <div class="required-note">
            <i class="fas fa-circle-info"></i>
            <span>{{ $hint }}</span>
        </div>
    @endif
</div>
