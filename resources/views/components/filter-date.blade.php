<div>
    <div class="input-group">
        <label for="date_filter_begin"
               class="visually-hidden"
        > Date Filter Begin </label>
        <div class="input-group-text">
            Filter Tanggal
        </div>
        <input
                id="date_filter_begin"
                name="date_filter_begin"
                class="form-control"
                wire:model="date_filter_begin"
                type="date"
        >
        <div class="input-group-text">
            s/d
        </div>
        <label for="date_filter_end"
               class="visually-hidden"
        > Date Filter End </label>
        <input
                id="date_filter_end"
                name="date_filter_end"
                class="form-control"
                wire:model="date_filter_end"
                type="date"
        >
    </div>
</div>