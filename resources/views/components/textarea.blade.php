<div class="my-3">
    <label for="{{ $field }}"> {{ $label }} </label>
    <textarea
            id="{{ $field }}"
            type="{{ $type ?? 'text' }}"
            placeholder="{{ $label }}"
            class="form-control @error($field)  @enderror"
            name="{{ $field }}"
            rows="5"
            cols="4"
    >{{ old($field, $value ?? null) }}</textarea>
    @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror
</div>