<?php


namespace App\Support;

/* @mixin \Livewire\Component */
/* @mixin \Livewire\WithPagination */
trait WithFilter
{
    public string $filter = "";

    public function initializeWithFilter()
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