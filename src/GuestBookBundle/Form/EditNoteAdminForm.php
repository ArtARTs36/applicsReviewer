<?php

namespace GuestBookBundle\Form;

use AppBundle\Entity\Work;
use AppBundle\Entity\WorkService;
use AppBundle\Helper\IconHelper;
use GuestBookBundle\Entity\Note;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditNoteAdminForm extends AbstractType
{
    const FIELD_NAME = 'name';
    const FIELD_MESSAGE = 'message';
    const FIELD_ACTIVE = 'active';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_NAME, null, ['label' => 'Имя']);

        $builder->add(self::FIELD_MESSAGE, null, ['label' => 'Содержание']);

        $builder->add('rating', ChoiceType::class,
            [
                'choices' => [
                    'Отлично' => Note::RATING_FINE,
                    'Хорошо' => Note::RATING_GOOD,
                    'Нейтрально' => Note::RATING_NEUTRAL,
                    'Плохо' => Note::RATING_BAD,
                    'Ужасно' => Note::RATING_AWFUL,
                ],
                'label' => 'Оценка'
            ]);

        $builder->add(self::FIELD_ACTIVE, ChoiceType::class, [
            'label' => 'Показывать на странице отзывов:',
            'choices' => [
                'Показывать' => true,
                'НЕ показывать' => false
            ],
            'multiple' => false,
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
            'data_class' => Note::class,
            'isNewEntity' => false
        ]);
    }
}
