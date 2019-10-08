<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditPushAllSettingsForm extends AbstractType
{
    const FIELD_API_KEY = 'apiKey';
    const FIELD_APPLICATION_ID = 'applicationId';
    const FIELD_FEED_LINK = 'feedLink';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_API_KEY, null, ['label' => 'Ключ для API']);

        $builder->add(self::FIELD_APPLICATION_ID, null, ['label' => 'ID приложения']);

        $builder->add(self::FIELD_FEED_LINK, null, ['label' => 'Пригласительная ссылка']);

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