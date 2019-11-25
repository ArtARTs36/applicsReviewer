<?php

namespace AppBundle\Service\SEO;

use ApplicBundle\Entity\OfferDocument;

class SEOVarsForArrayTwig
{
    /**
     * @param array
     * @param OfferDocument $obj
     * @param string $fieldName
     * @return array|false
     */
    public static function append(&$array, $fieldName)
    {
        $obj = $array[$fieldName];

        if (!is_object($obj)) {
            return false;
        }

        $array['seoKeywords'] = $obj->getSeoKeywords();
        $array['seoDescription'] = $obj->getSeoDescription();
        $array['seoTitle'] = $obj->getSeoTitle();

        return $array;
    }
}
