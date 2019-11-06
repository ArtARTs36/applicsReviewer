<?php

namespace AppBundle\Form;

use AppBundle\Entity\Work;
use AppBundle\Entity\WorkService;
use AppBundle\Helper\IconHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditWorkService extends AbstractType
{
    const FIELD_NAME = 'name';
    const FIELD_CONTENT = 'content';
    const FIELD_WORK = 'work';
    const FIELD_ICON = 'icon';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_NAME, null, ['label' => 'Название']);
        $builder->add(self::FIELD_CONTENT, null, ['label' => 'Содержание']);
        $builder->add(self::FIELD_ICON, TextType::class, ['label' => 'Иконка']);
        $builder->add(self::FIELD_WORK, EntityType::class, [
            'label' => 'Блок дел:',
            'class' => Work::class,
            'multiple' => false,
            'disabled' => !$options['isNewEntity']
        ]);

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
            'data_class' => WorkService::class,
            'isNewEntity' => false
        ]);
    }
}
