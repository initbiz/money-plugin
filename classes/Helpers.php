<?php declare(strict_types=1);

namespace Initbiz\Money\Classes;

class Helpers
{
    /**
     * Removes all non numeric characters from string
     * @param  string $value String with bloat
     * @return string        clear number string
     */
    public static function removeNonNumeric(String $value): String
    {
        $value = $value;
        $value = preg_replace('/[^0-9]|\s/', "", $value);
        return $value;
    }
}
