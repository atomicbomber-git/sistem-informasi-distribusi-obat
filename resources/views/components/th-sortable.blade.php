<th>
    <div
            wire:click="setSortBy('{{ $field }}')"
            style="display: flex; justify-content: space-between">
        {{ $slot }}

        @if($this->sortBy === $field)
            @if($this->sortDirection === 'asc')
                <i class="fa fa-chevron-down"
                   aria-hidden="true"
                ></i>

            @elseif($this->sortDirection === 'desc')
                <i class="fa fa-chevron-up"
                   aria-hidden="true"
                ></i>
            @endif
        @endif
    </div>
</th>

