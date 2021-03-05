<div class="mb-3">
    <label
            for="{{ $field }}"
            class="form-label @if($inline ?? false) visually-hidden @endif"
    >
        {{ $label }}
    </label>
    <input
            @if($livewire ?? false) wire:model.lazy="{{ $field }}" @endif
            id="{{ $field }}"
            type="{{ $type ?? 'text' }}"
            placeholder="{{ $label }}"
            class="form-control {{ ($small ?? false) ? "form-control-sm" : ""   }}  @error($field) is-invalid @enderror"
            name="{{ $field }}"
            value="{{ old($field, $value ?? null) }}"
    />
    @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror
</div>