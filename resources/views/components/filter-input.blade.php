<div class="flex-grow-1 me-2">
    <label for="filter" class="visually-hidden">
        @lang("application.filter")
    </label>

    <div class="input-group">
        <div class="input-group-text">
            <i class="bi-search"></i>
        </div>

        <input
                wire:model.debounce.500ms="filter"
                autofocus
                placeholder="@lang("application.filter")"
                class="form-control"
                id="filter"
                type="text"
        >
    </div>
</div>

