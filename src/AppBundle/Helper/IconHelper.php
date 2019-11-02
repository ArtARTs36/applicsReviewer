<?php

namespace AppBundle\Helper;

class IconHelper
{
    public static $iconClasses = [
        0 => 'fas fa-people-carry',
        1 => 'fas fa-briefcase',
        2 => 'fas fa-car'
    ];

    public static function getInversionIconClasses()
    {
        return array_flip(self::$iconClasses);
    }

    public static function getClass($icon)
    {
        return self::$iconClasses[$icon];
    }
}
