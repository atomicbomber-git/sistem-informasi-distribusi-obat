<?php


namespace App\Support;


use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

/** @mixin Component */
trait HasValidatorThatEmitsErrors
{
    public function validateAndEmitErrors($rules = null, $messages = [], $attributes = []): array
    {
        try {
             return $this->validate($rules, $messages, $attributes);
        } catch (ValidationException $validationException) {
            $this->emit("validation-errors", $validationException->errors());
            throw $validationException;
        }
    }
}