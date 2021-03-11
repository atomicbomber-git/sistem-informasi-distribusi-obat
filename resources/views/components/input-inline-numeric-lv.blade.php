<div
        x-data="{ value: @entangle($field) }"
        x-init="
        (function () {
            let cleave = new Cleave($refs.input, {
                numeral: true,
                onValueChanged: e => { value = e.target.rawValue }
            })

            cleave.setRawValue(value)

            $watch('value', newValue => {
                cleave.setRawValue(newValue)
            })
        })()
    "
>
    <label for="{{ $key }}" class="visually-hidden">
        {{ $label }}
    </label>

    <input
            class="form-control form-control-sm text-end"
            x-ref="input"
            id="{{ $key }}"
            type="text"
    >
</div>