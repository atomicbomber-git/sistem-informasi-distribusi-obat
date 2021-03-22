<?php

if (!function_exists("mbcmul")) {
    function mbcmul(string ...$args) {
        $result = "1";

        foreach ($args as $arg) {
            $result = bcmul($result, $arg);
        }

        return $result;
    }
}