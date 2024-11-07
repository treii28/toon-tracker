<?php

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
