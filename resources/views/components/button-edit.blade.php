<a
        {{ $attributes->except("class") }}
        class="btn btn-info btn-sm {{ $class ?? "" }}" >
        {{ $slot }}
        <x-icon-edit/>
</a>