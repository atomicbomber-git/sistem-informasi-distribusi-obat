<div class="mb-3">
    <label
            for="{{ $field }}"
            class="form-label @if($inline ?? false) visually-hidden @endif"
    >
        {{ $label }}
    </label>

    @if($group ?? false) <div class="input-group"> @endif

    {{ $input_prefix ?? null }}
    <input
            {{ $attributes->except(["inline", "field", "small", "type", "livewire"]) }}
            @if($livewire ?? false) wire:model.lazy="{{ $field }}" @endif
            {{ ($removed ?? false) ? "disabled" : "" }}
            id="{{ $field }}"
            type="{{ $type ?? 'text' }}"
            placeholder="{{ $label }}"
            class="form-control {{ ($small ?? false) ? "form-control-sm" : "" }} @error($field) is-invalid @enderror"
            name="{{ $field }}"
            @if($help ?? false) aria-describedby="{{ $field }}_help" @endif
    />

        @if($help ?? false)
            <div id="{{ $field }}_help" class="form-text">
                {{ $help }}
            </div>
        @endif

        @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror

    @if($group ?? false) </div> @endif

</div>