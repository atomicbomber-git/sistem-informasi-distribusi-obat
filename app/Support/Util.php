<?php


namespace App\Support;


class Util
{
    public static function normalizeNumber(float|int $number): float|int
    {
        return $number + 0;
    }
}