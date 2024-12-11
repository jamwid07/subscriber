<?php

namespace App\Form;

use App\Entity\PaymentInformation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cardNumber', TextType::class, [
                'label' => 'Credit Card Number',
                'attr' => [
                    'class' => 'form-control',
                    'data-mask' => '0000 0000 0000 0000',
                    'data-mask-clearifnotmatch' => true,
                ],
            ])
            ->add('expirationDate', TextType::class, [
                'label' => 'Expiration Date (MM/YY)',
                'attr' => [
                    'class' => 'form-control',
                    'data-mask' => '00/00',
                ],
            ])
            ->add('cvv', TextType::class, [
                'label' => 'CVV',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 3,
                    'data-mask' => '000',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaymentInformation::class,
        ]);
    }
}