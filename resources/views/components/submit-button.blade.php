<button
        {{ $attributes->except('class') }}
        class="btn btn-primary {{ $class ?? '' }}"
>
        {{ $slot }}
</button>