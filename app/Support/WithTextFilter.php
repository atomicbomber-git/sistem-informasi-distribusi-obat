<?php


namespace App\Support;

/* @mixin \Livewire\Component */
/* @mixin \Livewire\WithPagination */
trait WithTextFilter
{
    public string $filter = "";

    public function initializeWithTextFilter()
    {
        $this->queryString = array_merge($this->queryString, [
            "filter" => ["except" => ""],
        ]);
    }

    public function updatedFilter()
    {
        $this->resetPage();
    }
}