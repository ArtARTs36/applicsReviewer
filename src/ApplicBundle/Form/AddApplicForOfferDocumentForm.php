<?php

namespace ApplicBundle\Form;

use ApplicBundle\Entity\Applic;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddApplicForOfferDocumentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('clientName', TextType::class, [
                'label' => 'Как к Вам обращаться?'
            ])

            ->add('clientPhone', TextType::class, [
                'label' => 'Ваш номер телефона:'
            ])

            ->add('clientMail', EmailType::class, [
                'label' => 'Ваш электронный адрес:',
                'required' => false
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
            'data_class' => Applic::class,
        ]);
    }
}