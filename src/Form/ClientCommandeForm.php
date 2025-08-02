<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCommandeForm extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civilite', ChoiceType::class, [
                'label' => 'Civilité',
                'mapped' => false,
                'required' => true,
                'choices' => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame',
                    'Mademoiselle' => 'Mademoiselle',
                ],
                'placeholder' => 'Choisir une civilité',
            ])
            ->add('prenoms',TextType::class,$this->getConfiguration('Prénom','Prénoms du client',[
                'required' => true,
            ]))
            ->add('nom',TextType::class,$this->getConfiguration('Nom','Nom du client',[
                'required' => true,
            ]))
            ->add('telephone',TextType::class,$this->getConfiguration('Téléphone','Téléphone du client',[
                'required' => true,
            ]))
            ->add('email',TextType::class,$this->getConfiguration('Email','Email du client',[
                'required' => false,
            ]))
            ->add('optionLivraison',ChoiceType::class,[
                'choices' =>[
                    'Sur place' => 'Sur place',
                    'A Livrer' => 'hors',

                ],
                'attr'=> ['onclick'=>'toggleDiv()'],
                'expanded' => true,
                'multiple' => false,
                'required'=>'true',
                'mapped'=>false
            ])

            ->add('adresse',TextType::class,$this->getConfiguration('Adresse','Adresse du client',[
                'required' => false,
            ]))
            ->add('observations',TextAreaType::class,$this->getConfiguration('Observation','Observations',[
                'required' => false,
                'mapped' => false,
            ]))





        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
