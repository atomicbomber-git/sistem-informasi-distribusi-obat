<div class="form-group">
    <input
            name="{{ $field }}"
            type="hidden"
            value="0"
    >

    <div class="checkbox">
        <label for="{{ $field }}">
            <input
                    id="{{ $field }}"
                    value="1"
                    {{ old($field, $value ?? null) ? "checked" : "" }}
                    name="{{ $field }}"
                    type="checkbox"
            >
            {{ $label }}
        </label>

        @error("$field")
        <span class="invalid-feedback text-danger d-block">
            {{ $message }}
        </span>
        @enderror
    </div>
</div>