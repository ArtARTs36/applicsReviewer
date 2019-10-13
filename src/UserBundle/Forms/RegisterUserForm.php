<?php

namespace UserBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;

class RegisterUserForm extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', TextType::class, [
            'label' => 'User Name'
        ]);

        $builder->add('patronymic', TextType::class, [
            'label' => 'Patronymic'
        ]);

        $builder->add('firstName', TextType::class, [
            'label' => 'First Name'
        ]);

        $builder->add('lastName', TextType::class, [
            'label' => 'Last Name'
        ]);

        $builder->add('email', EmailType::class, array(
            'invalid_message' => 'The email address is invalid.',
            'required' => true,
        ));

        $builder->add('password', PasswordType::class, array(
            'invalid_message' => 'The password fields must match.',
            'required' => true,
        ));

        $builder->add('submit', SubmitType::class, [
            'label' => 'Зарегистрироваться'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
