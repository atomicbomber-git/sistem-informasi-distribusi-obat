<a
        {{ $attributes->except("classes") }}
        class="nav-link text-decoration-underline {{ $class ?? '' }} {{ request()->routeIs($routeIs) ? "active fw-bold" : "" }}"

        @if(request()->routeIs($routeIs))
        aria-current="page"
        @endif

>
    {{ $slot }}
</a>