<form
        {{ $attributes  }}
        enctype="multipart/form-data"
        method="POST"
>
    @csrf
    @method("PATCH")

    {{ $slot }}
</form>