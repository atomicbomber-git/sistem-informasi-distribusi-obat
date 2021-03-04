<?php


namespace App\Support;

use App\Enums\MessageState;
use Illuminate\Support\Str;
use Livewire\Component;

/* @mixin Component */
trait WithDestroy
{
    public function initializeWithDestroy()
    {
        $this->listeners = array_merge($this->listeners, [
            "destroy" => "destroy",
        ]);
    }

    public function destroy(mixed $modelKey)
    {
        try {
            $modelClass = "\\App\\Models\\" . Str::before(class_basename(self::class), "Index");

            $modelClass::query()
                ->whereKey($modelKey)
                ->delete();

            $this->resetPage();

            SessionHelper::flashMessage(
                __("messages.delete.success"),
                MessageState::STATE_SUCCESS,
            );
        } catch (\Throwable $throwable) {
            ray($throwable);

            SessionHelper::flashMessage(
                __("messages.delete.failure"),
                MessageState::STATE_DANGER,
            );
        }
    }
}