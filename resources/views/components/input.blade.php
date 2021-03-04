<div class="form-group">
    <label
            for="{{ $field }}"
            class="form-label"
    >
        {{ $label }}
    </label>
    <input
            id="{{ $field }}"
            type="{{ $type ?? 'text' }}"
            placeholder="{{ $label }}"
            class="form-control @error($field) is-invalid @enderror"
            name="{{ $field }}"
            value="{{ old($field, $value ?? null) }}"
    />
    @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror
</div>