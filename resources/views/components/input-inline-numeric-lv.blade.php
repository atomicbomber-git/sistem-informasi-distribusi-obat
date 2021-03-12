<div
        wire:ignore
        x-data="{ value: @entangle($field), errors: [] }"
        x-init="
        (function () {
            Livewire.on('validation-errors', errorBag => {
                if (errorBag.hasOwnProperty('{{ $field }}')) {
                    errors = errorBag['{{ $field }}']
                } else {
                    errors = []
                }
            })

            let cleave = new Cleave($refs.input, {
                numeral: true,
                onValueChanged: e => { value = e.target.rawValue }
            })

            cleave.setRawValue(value)

            $watch('value', newValue => {
                if (typeof newValue !== 'undefined') {
                    cleave.setRawValue(newValue)
                }
            })
        })()
    "
>
    <label for="{{ $key }}"
           class="visually-hidden"
    >
        {{ $label }}
    </label>

    <input
            id="{{ $key }}"
            class="form-control form-control-sm text-end"
            x-bind:class="{ 'is-invalid': errors.length > 0 }"
            x-ref="input"
            type="text"
    >
    <span class="invalid-feedback text-danger"
          x-show="errors.length > 0"
          x-text="errors[0] ?? ''"
    >
    </span>
</div>