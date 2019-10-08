<?php

namespace AppBundle\Form;

use AppBundle\Entity\CourtPractice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCourtPracticesForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('theme', null, ['label' => 'Тема']);

        $builder->add('content', TextAreaType::class, ['label' => 'Содержание']);

        $builder->add('priority', ChoiceType::class,
            [
                'choices' => [
                    'Высокий' => CourtPractice::PRIORITY_MAX,
                    'Средний' => CourtPractice::PRIORITY_MIDDLE,
                    'Низкий' => CourtPractice::PRIORITY_MIN,
                ],
                'label' => 'Приоритет'
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
            'data_class' => CourtPractice::class,
        ]);
    }
}