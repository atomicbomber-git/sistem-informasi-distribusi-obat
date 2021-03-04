<div wire:ignore>
    <label for="{{ $field }}" class="visually-hidden">
        {{ $label }}
    </label>
    <input
            value="{{ data_get($this, $field) }}"
            class="form-control form-control-sm text-end @error($field) is-invalid @enderror"
            id="{{ $key }}"
            type="text">
    @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror

    <script type="application/javascript">
        (function() {
            let old = null

            let setup = () => {
                let cleave = new Cleave("#{{ $key }}", {
                    numeral: true,
                    onValueChanged: function (e) {
                    @this.set("{{ $field }}", e.target.rawValue)
                        old = e.target.rawValue
                    }
                })

                window.Livewire.on('set:{{ $field }}',  value => {
                    if (old !== value) {
                        cleave.setRawValue(value)
                    }
                })
            }

            if (document.readyState !== "complete") {
                window.addEventListener("load", setup)
            } else {
                setup()
            }
        })()
    </script>
</div>
