<?php


namespace App\Support;


use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

class Formatter
{
    public static function percentage($number): string
    {
        return number_format($number  * 100) . " %";
    }

    public static function dayMonthYear($value): string
    {
        if (!empty($value)) {
            return Date::make($value)->format("d/m/Y");
        }

        return "-";
    }

    public static function dateTime($value): string
    {
        if (!empty($value)) {
            return Date::make($value)->format("m/d/Y");
        }

        return "-";
    }

    public static function humanDiff($value): string
    {
        if (!empty($value)) {
            return Date::make($value)->diffForHumans(
                today(),
                CarbonInterface::DIFF_RELATIVE_TO_NOW
            );
        }

        return "-";
    }

    public static function normalizedNumeral($value): string
    {
        return number_format($value, 0, "", "");
    }

    public static function currency($input): string
    {
        return number_format($input);
    }

    public static function quantity($input): string
    {
        return number_format($input);
    }

    public static function debitCredit(float $input): string
    {
        return self::currency(abs($input));
    }
}
