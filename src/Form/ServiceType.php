<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name'
            ])
            ->add('avg_duration_in_minutes', ChoiceType::class, [
                'label' => 'Durchschn. benötigte Zeit in Min.',
                'choices' => [
                    '15 Minuten' => 15,
                    '30 Minuten' => 30,
                    '45 Minuten' => 45,
                    '60 Minuten' => 60,
                    '75 Minuten (1 Stunde 15 Minuten)' => 75,
                    '90 Minuten (1 Stunde 30 Minuten)' => 90,
                    '105 Minuten (1 Stunde 45 Minuten)' => 105,
                    '120 Minuten (2 Stunden)' => 120,
                    '135 Minuten (2 Stunden 15 Minuten)' => 135,
                    '150 Minuten (2 Stunden 30 Minuten)' => 150,
                    '165 Minuten (2 Stunden 45 Minuten)' => 165,
                    '180 Minuten (3 Stunden)' => 180
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
