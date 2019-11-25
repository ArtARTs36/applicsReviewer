<?php

namespace AppBundle\Helper;

class TextHelper
{
    const PATTERN_FIND_LINKS = '#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#';
    const RUSSIAN_ALPHABET = [
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н',
        'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ь', 'ы', 'ъ', 'э', 'ю', 'я',
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р',
        'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ь', 'Ы', 'Ъ', 'Э', 'Ю', 'Я'
    ];

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

    public static function isExistsRussianWords($text)
    {
        $words = explode(' ', $text);
        foreach ($words as $word) {
            if (self::isRussianWord($word)) {
                return true;
            }
        }

        return false;
    }

    public static function isRussianWord($word)
    {
        $array = preg_split('//u', $word,  null, PREG_SPLIT_NO_EMPTY);
        if (count($array) < 2) {
            return false;
        }

        foreach ($array as $value) {
            if (in_array($value, self::RUSSIAN_ALPHABET)) {
                return true;
            }
        }

        return false;
    }

    private static function retArray($array)
    {
        return (empty($array[0])) ? null : $array;
    }
}
