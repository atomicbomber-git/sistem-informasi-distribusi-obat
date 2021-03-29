@props([
    "searchUrl",
    "label",
    "field",
    "theme" => "bootstrap-5"
])

<div class="mb-3"
     wire:ignore
     {{ $attributes->wire('model') }}
>
    <label for="{{ $field }}"> {{ $label  }} </label>
    <select
            class="form-control form-control-sm"
            id="{{ $field }}"
            name="{{ $field }}"
            x-data
            x-init="
$($el).select2({
    ajax: {  url: '{{ $searchUrl }}' },
    theme: '{{ $theme }}'
}).change(e => {
    $dispatch('input', e.target.value)
})
">
        {{ $slot  }}
    </select>
</div>