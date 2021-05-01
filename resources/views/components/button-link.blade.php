<a
        {{ $attributes->except(["small", "class"]) }}
        class="btn btn-info {{ ($small ?? false) ? "btn-sm" : "" }} {{ $class ?? "" }}" >
        {{ $slot }}
</a>