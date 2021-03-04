<div class="form-group">
    <label for="{{ $field }}"> {{ $label }} </label>
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
            })
        </script>
    @endpush
</div>