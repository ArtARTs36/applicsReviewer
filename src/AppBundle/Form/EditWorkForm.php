<?php

namespace AppBundle\Form;

use AppBundle\Entity\Work;
use AppBundle\Entity\WorkService;
use AppBundle\Helper\IconHelper;
use AppBundle\Service\SEO\SEOFieldsForFormBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditWorkForm extends AbstractType
{
    const FIELD_NAME = 'name';
    const FIELD_CONTENT = 'content';
    const FIELD_URL = 'url';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_NAME, null, ['label' => 'Название']);
        $builder->add(self::FIELD_URL, null, ['label' => 'Относительный путь: "/ugolovnoe"']);
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
            'data_class' => Work::class,
            'isNewEntity' => false
        ]);
    }
}
