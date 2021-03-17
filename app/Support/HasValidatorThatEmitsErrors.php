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
            throw $this->emitErrors($validationException);
        }
    }

    public function emitErrors(ValidationException $exception): ValidationException
    {
        $this->emit("validation-errors", $exception->errors());
        return $exception;
    }
}