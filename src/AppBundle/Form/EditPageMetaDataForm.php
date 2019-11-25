<?php

namespace AppBundle\Form;

use AppBundle\Entity\PageMetaData;
use AppBundle\Service\SEO\SEOFieldsForFormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPageMetaDataForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, ['label' => 'Название страницы']);
        SEOFieldsForFormBuilder::appendFieldsForBuilder($builder);

        $builder->add('save', SubmitType::class, [
            'label' => 'Отправить',
            'attr' => [
                'class' => 'btn btn-primary btn-lg btn-bloc'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PageMetaData::class
        ]);
    }
}
