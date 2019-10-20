<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditDesignForm extends AbstractType
{
    const FIELD_FOOTER_CONTACT_PHONE_1 = 'footer_phone_1';
    const FIELD_FOOTER_CONTACT_PHONE_2 = 'footer_phone_2';
    const FIELD_FOOTER_ADDRESS = 'footer_address';
    const FIELD_FOOTER_EMAIL = 'footer_email';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_FOOTER_ADDRESS, null, ['label' => 'Адрес']);
        $builder->add(self::FIELD_FOOTER_EMAIL, null, ['label' => 'Футер. Электронный адрес']);
        $builder->add(self::FIELD_FOOTER_CONTACT_PHONE_1, null, ['label' => 'Футер. Номер телефона #1']);
        $builder->add(self::FIELD_FOOTER_CONTACT_PHONE_2, null, ['label' => 'Футер. Номер телефона #2']);

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
            'data_class' => null
        ]);
    }
}
