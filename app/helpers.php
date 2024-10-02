<?php

if(! function_exists('dupeKeys')) {
    /**
     * turn an indexed array into an associative array using $value => $value for key/value pairs.
     * (useful for populating select options when an associative array is expected)
     *
     * @param array $array
     * @return array
     */
    function dupeKeys(array $array): array
    {
        $newArray = [];
        foreach($array as $value)
            $newArray[$value] = $value;
        return $newArray;
    }
}
