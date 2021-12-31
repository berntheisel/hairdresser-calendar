<?php

namespace App\Form;

use App\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dayStartTime', TextType::class, [
                'label' => 'Tagesbeginn (Bsp.: 8 Uhr => 08:00:00)'
            ])
            ->add('dayEndTime', TextType::class, [
                'label' => 'Tagesende (Bsp.: 20 Uhr => 20:00:00)'
            ])
            ->add('minuteSteps', ChoiceType::class, [
                'label' => 'Minutenintervall für Termine',
                'choices' => [
                    '5' => 5,
                    '10' => 10,
                    '15' => 15,
                    '20' => 20,
                    '25' => 25,
                    '30' => 30,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
