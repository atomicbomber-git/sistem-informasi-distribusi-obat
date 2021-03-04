<a
        {{ $attributes->except("class") }}
        class="btn btn-info {{ $class ?? "" }}" >
        {{ $slot }}
</a>