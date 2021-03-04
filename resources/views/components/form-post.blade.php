<form
        {{ $attributes  }}
        enctype="multipart/form-data"
        method="POST"
>
    @csrf
    @method("POST")

    {{ $slot }}
</form>