<button
        x-data="{}"
        x-on:click="confirmDialog().then(res => res.isConfirmed && Livewire.emit('destroy', '{{ $item->getKey() }}'))"
        class="btn btn-danger btn-sm"
>
    @lang("application.destroy")
    <i class="bi-trash"></i>
</button>