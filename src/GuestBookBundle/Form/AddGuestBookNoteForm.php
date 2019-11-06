<?php

namespace GuestBookBundle\Form;

use GuestBookBundle\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddGuestBookNoteForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, ['label' => 'Ваше имя']);

        $builder->add('message', TextAreaType::class, ['label' => 'Содержание']);

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
        ]);
    }
}
