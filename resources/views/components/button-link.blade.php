<a
        {{ $attributes->except("class") }}
        class="btn btn-info {{ ($small ?? false) ? "btn-sm" : "" }} {{ $class ?? "" }}" >
        {{ $slot }}
</a>