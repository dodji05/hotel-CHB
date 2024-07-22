<?php

namespace App\Form;

use App\Model\Reservation;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateArrive',DateType::class,$this->getConfiguration("Date d'arrivé","Date d'arrivé",[
        'required'=>false
    ]))
            ->add('dateDepart',DateType::class,$this->getConfiguration("Date de depart","Date de depart",[
                'required'=>false
            ]))
            ->add('nbAdulte', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'placeholder' => "Nombre d'adulte",
                'required' => false])
            ->add('nbEnfant', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'placeholder' => "Nombre d'enfants",
                'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
