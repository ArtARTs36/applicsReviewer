<?php

namespace AppBundle\Service\SEO;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SEOFieldsForFormBuilder
{
    const FIELD_SEO_KEYWORDS = 'seo_keywords';
    const FIELD_SEO_DESCRIPTION = 'seo_description';
    const FIELD_SEO_TITLE = 'seo_title';

    public static function appendFieldsForBuilder(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_SEO_KEYWORDS, TextareaType::class, [
            'label' => 'SEO: keywords (теги через запятую)',
            'required' => false,
        ]);

        $builder->add(self::FIELD_SEO_DESCRIPTION, TextareaType::class, [
            'label' => 'SEO: description',
            'required' => false,
        ]);

        $builder->add(self::FIELD_SEO_TITLE, TextType::class, [
            'label' => 'SEO: title',
            'required' => false,
        ]);
    }
}
