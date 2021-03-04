<?php


namespace App\Support;

use Livewire\WithPagination;

/* @mixin \Livewire\Component|\Livewire\WithPagination */
trait WithSort
{
    public $sortBy = null;
    public $sortDirection = "asc";

    public function initializeWithSort()
    {
        $this->queryString = array_merge($this->queryString, [
            "sortBy" => ["except" => null],
            "sortDirection" => ["except" => "asc"],
        ]);

        $this->listeners = array_merge($this->listeners, [
            "setSortBy" => "setSortBy",
        ]);
    }
    public function setSortBy(string $columnName)
    {
        $this->resetPage();

        if ($this->sortBy === $columnName) {
            if ($this->sortDirection === "asc") {
                $this->sortDirection = "desc";
            } else {
                $this->sortBy = null;
                $this->sortDirection = "asc";
            }
        } else {
            $this->sortBy = $columnName;
            $this->sortDirection = "asc";
        }
    }
}