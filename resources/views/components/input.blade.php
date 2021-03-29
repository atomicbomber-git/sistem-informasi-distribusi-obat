<div class="mb-3">
    <label
            for="{{ $field }}"
            class="form-label @if($inline ?? false) visually-hidden @endif"
    >
        {{ $label }}
    </label>
    <input
            {{ $attributes->except(["inline", "field", "small", "type", "livewire"]) }}
            @if($livewire ?? false) wire:model.lazy="{{ $field }}" @endif
            {{ ($removed ?? false) ? "disabled" : "" }}
            id="{{ $field }}"
            type="{{ $type ?? 'text' }}"
            placeholder="{{ $label }}"
            class="form-control {{ ($small ?? false) ? "form-control-sm" : "" }} @error($field) is-invalid @enderror"
            name="{{ $field }}"
    />
    @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror
</div>