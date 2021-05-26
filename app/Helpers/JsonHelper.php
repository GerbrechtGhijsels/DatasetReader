<?php

namespace App\Helpers;


class FileHelper
{
    /**
     * Checks if a string is a json string
     *
     * @param $string string
     * @return bool True of False based on if the string is a json string
     */
    function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

