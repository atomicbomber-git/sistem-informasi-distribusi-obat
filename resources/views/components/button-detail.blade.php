<a
        {{ $attributes->except("class") }}
        class="btn btn-info btn-sm {{ $class ?? "" }}" >
        {{ $slot }}
        <i class="bi-list-task"></i>
</a>