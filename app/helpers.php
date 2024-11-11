<?php

if(!function_exists('progress_bar')) {
    function progress_bar($done, $total, $info = "", $width = 50)
    {
        if (empty($info)) $info = sprintf(" %d/%d", $done, $total);
        $perc = round(($done * 100) / $total);
        $bar = round(($width * $perc) / 100);
        return (($done >= $total) ? PHP_EOL : sprintf("%s%%[%s>%s] %d/%d %s...\r", $perc, str_repeat("=", $bar), str_repeat(" ", $width - $bar), $done, $total, $info));
    }
}
if(!function_exists('parse_list')) {
    /**
     * Parse a string into an array of strings
     *
     * @param string $string
     * @param string $delimiter
     * @return array
     */
    function parse_list(string $string, string $delimiter = ','): array
    {
        return array_map(
            function($item) { return trim($item, " \t\n\r\0\x0B\"'"); },
            explode($delimiter, $string)
        );
    }
}
if(!function_exists('download_path')) {
    function download_path(string $filename): string
    {
        return database_path('seeders/download/' . $filename);
    }
}
if(!function_exists('download_path')) {
    function exports_path(string $filename): string
    {
        return exports_path('seeders/exports/' . $filename);
    }
}


if(!function_exists('is_json')) {
    /**
     * Check if a string is a valid JSON string
     *
     * @param string $string
     * @return bool
     */
    function is_json(mixed $string): bool
    {
        if(!is_string($string))
            return false;
        json_decode($string);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
