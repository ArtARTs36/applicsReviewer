<?php

namespace AppBundle\Helper;

class TextHelper
{
    const PATTERN_FIND_LINKS = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';

    public static function getLinks($text)
    {
        preg_match_all(self::PATTERN_FIND_LINKS, $text, $matches);

        return self::retArray($matches[0]);
    }

    public static function isExistsLinks($text)
    {
        return preg_match(self::PATTERN_FIND_LINKS, $text) ? true : false;
    }

    public static function isExistsHTMLTags($text)
    {
        return ($text != strip_tags($text));
    }

    private static function retArray($array)
    {
        return (empty($array[0])) ? null : $array;
    }
}
