<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ChoiceType::class, [
                'label' => 'Titel',
                'choices' => [
                    'Herr' => 1,
                    'Frau' => 2,
                    'Divers' => 3
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Vorname',
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nachname'
            ])
            ->add('email', TextType::class, [
                'label' => 'E-Mail'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Telefon'
            ])
            ->add('mobile', TextType::class, [
                'label' => 'Mobil'
            ])
            ->add('street', TextType::class, [
                'label' => 'Strasse'
            ])
            ->add('zip', TextType::class, [
                'label' => 'Postleitzahl'
            ])
            ->add('city', TextType::class, [
                'label' => 'Stadt'
            ])
            ->add('country', TextType::class, [
                'label' => 'Land'
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Geburtstag',
                'widget' => 'single_text',
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Notiz'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
