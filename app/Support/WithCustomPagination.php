<?php


namespace App\Support;


use Livewire\WithPagination;

trait WithCustomPagination
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
}