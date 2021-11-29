<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ChoiceType::class, [
                'choices' => [
                    'Herr' => 1,
                    'Frau' => 2,
                    'Divers' => 3
                ]
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('phone')
            ->add('mobile')
            ->add('street')
            ->add('zip')
            ->add('city')
            ->add('country')
            ->add('birthday')
            ->add('note')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
