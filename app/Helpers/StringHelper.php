<?php

namespace App\Helpers;

class StringHelper
{
    public static function addSlashes($value)
    {
        return addcslashes($value, '%_');
    }

    public static function formatFileSize($size, $precision = 2)
    {
        if ($size > 0) {
            $size = (int) $size;
            $base = log($size) / log(1024);
            $suffixes = array(' b', ' KB', ' MB', ' GB', ' TB');

            return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
        }
        else {
            return $size;
        }
    }
}
