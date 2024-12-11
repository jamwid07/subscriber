<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addressLine1', TextType::class, [
                'label' => 'Address Line 1',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('addressLine2', TextType::class, [
                'label' => 'Address Line 2',
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Postal Code',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('stateProvince', TextType::class, [
                'label' => 'State/Province',
                'required' => false,
                'attr' => [
                    'class' => 'form-control state-province-field',
                    'data-countries-with-states' => json_encode(['US', 'CA', 'GB', 'AU', 'SP']),
                ],
            ])
            ->add('country', CountryType::class, [
                'label' => 'Country',
                'attr' => ['class' => 'form-control country-select'],
                'preferred_choices' => ['US', 'CA', 'GB', 'FR', 'DE'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}