<?php

namespace ApplicBundle\Form;

use ApplicBundle\Entity\Applic;
use ApplicBundle\Entity\VocabBadClient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddBadClientForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'Отчество'
            ])
            ->add('family', TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Номер телефона'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Адрес электронной почты'
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Комментарий'
            ])
        ;

        $builder
            // ...
            ->add('save', SubmitType::class, ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VocabBadClient::class,
        ]);
    }
}
