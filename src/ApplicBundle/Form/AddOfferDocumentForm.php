<?php

namespace ApplicBundle\Form;

use AppBundle\Service\SEO\SEOFieldsForFormBuilder;
use ApplicBundle\Entity\OfferDocument;
use ApplicBundle\Entity\OfferDocumentDeliveryMethod;
use ApplicBundle\Entity\OfferDocumentRequiredDoc;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddOfferDocumentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', TextType::class, [
                'label' => 'Название документа'
            ])

            ->add('requiredDocuments', EntityType::class, [
                'label' => 'Список необходимых документов:',
                'class' => OfferDocumentRequiredDoc::class,
                'multiple' => true
            ])

            ->add('deliveryMethods', EntityType::class, [
                'label' => 'Способы доставки:',
                'class' => OfferDocumentDeliveryMethod::class,
                'multiple' => true
            ])

            ->add('price', TextType::class, [
                'label' => 'Стоимость:'
            ])
        ;

        SEOFieldsForFormBuilder::appendFieldsForBuilder($builder);

        $builder
            // ...
            ->add('save', SubmitType::class, ['label' => 'Отправить'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OfferDocument::class,
        ]);
    }
}