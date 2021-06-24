<?php


namespace App\Support;

/* @mixin \Livewire\WithPagination | \Livewire\Component  */
trait WithDateFilter
{
    public string $date_filter_begin = "";
    public string $date_filter_end = "";

    public function initializeWithDateFilter()
    {
        $this->queryString = array_merge($this->queryString, [
            "date_filter_begin" => ["except" => ""],
            "date_filter_end" => ["except" => ""],
        ]);
    }

    public function updatedDateFilterBegin()
    {
        $this->resetPage();
    }

    public function updatedDateFilterEnd()
    {
        $this->resetPage();
    }
}