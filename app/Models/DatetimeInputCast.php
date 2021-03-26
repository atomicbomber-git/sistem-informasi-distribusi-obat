<?php


namespace App\Models;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Jenssegers\Date\Date;

class DatetimeInputCast implements CastsAttributes
{
    public function set($model, string $key, $value, array $attributes)
    {
        return Date::make($value)->format("Y-m-d H:i:s");
    }

    public function get($model, string $key, $value, array $attributes)
    {
        return Date::make($value)->format("Y-m-d\TH:i");
    }
}