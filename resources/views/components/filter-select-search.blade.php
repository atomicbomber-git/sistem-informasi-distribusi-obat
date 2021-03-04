<div wire:ignore class="form-group">
    <label class="sr-only" for="{{ $field }}"> {{ $label }} </label>
    <select
            id="{{ $field }}"
            type="text"
            class="form-control @error("$field") error @enderror"
            name="{{ $field }}"
    >
        @if($value ?? false)
            <option value="{{ $value->getKey() }}">
                {{ $value->getLabel() }}
            </option>
        @endif
    </select>

    @error($field)
    <span class="invalid-feedback text-danger">
        {{ $message }}
    </span>
    @enderror

    @push("scripts")
        <script type="application/javascript">
            $("#{{ $field}}").select2({
                placeholder: "{{ $label }}",
                ajax: { url: "{!! $searchUrl !!}" },
                theme: "bootstrap4"
            }).change(e => {
                @this.set('{{ $field }}', e.target.value)
            })
        </script>
    @endpush
</div>