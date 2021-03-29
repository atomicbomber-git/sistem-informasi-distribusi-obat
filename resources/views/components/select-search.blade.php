@props([
    "searchUrl",
    "label",
    "field" => $attributes->get("field") ?? $attributes->wire('model')->value(),
    "theme" => "bootstrap-5",
    "inline" => false,
])

<div class="mb-3"
     wire:ignore
     {{ $attributes->wire('model') }}
>
    <label for="{{ $field }}" class="{{ $inline ? "visually-hidden" : "" }}">
        {{ $label  }}
    </label>
    <select
            class="form-control form-control-sm"
            id="{{ $field }}"
            name="{{ $field }}"
            x-data
            x-init="
$($el).select2({
    placeholder: '{{ $label }}',
    ajax: {  url: '{{ $searchUrl }}' },
    theme: '{{ $theme }}',
    allowClear: true
}).change(e => {
    $dispatch('input', e.target.value)
})
">
        {{ $slot  }}
    </select>
</div>