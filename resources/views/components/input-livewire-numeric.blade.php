<div
        x-data="{ value: @entangle($attributes->wire('model')), error: null, cleave: null }"
        x-init="(function() {
            let field = '{{ $attributes->wire('model')->value() }}'

            Livewire.on('validation-errors', errorBag => {
                if (errorBag.hasOwnProperty(field)) {
                    error = errorBag[field][0]
                } else {
                    error = null
                }
            })

             cleave = new Cleave($refs.input, {
                numeral: true,
                onValueChanged: e => {
                    let rawValue = e.target.rawValue.length !== 0 ?
                        Number(e.target.rawValue) :
                        null

                    if (rawValue !== value) {
                        $dispatch('input', rawValue)
                    }
                }
            })

            $watch('value', newValue => {
                let rawValue = cleave.getRawValue().length !== 0 ?
                   Number(cleave.getRawValue()) :
                   null

               if (rawValue !== newValue) {
                   cleave.setRawValue(newValue)
               }
            })

            cleave.setRawValue(Number(value))
        })()"
        wire:ignore.self

        {{ $attributes->except(["field", "inline", "small"]) }}
>
    <label for="{{ $field ?? $attributes->wire('model')->value() }}"
           class="{{ ($inline ?? false) ? 'visually-hidden' : '' }}"
    >
        {{ $label }}
    </label>

    <input
            x-on:focusout="value = cleave.getRawValue()"
            x-on:change="$event.stopPropagation()"
            x-on:input="$event.stopPropagation()"
            id="{{ $field ?? $attributes->wire('model')->value() }}"
            class="form-control {{ ($small ?? false) ? "form-control-sm" : "" }} text-end"
            x-bind:class="{ 'is-invalid': error }"
            x-ref="input"
            type="text"
    >
    <span class="invalid-feedback text-danger"
          x-show="error"
          x-text="error ?? ''"
    >
    </span>
</div>
