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
            ->add('name', TextType::class)
            ->add('patronymic', TextType::class)
            ->add('family', TextType::class)
            ->add('phone', TextType::class)
            ->add('email', EmailType::class)
            ->add('comment', TextareaType::class)
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
