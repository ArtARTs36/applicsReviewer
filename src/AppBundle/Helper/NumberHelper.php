<?php

namespace AppBundle\Helper;

class NumberHelper
{
    public static function removeNumbers($words)
    {
        return preg_replace('/[0-9]+/', '', $words);
    }
}
