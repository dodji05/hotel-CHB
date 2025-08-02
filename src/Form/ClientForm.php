<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientForm extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('pays',CountryType::class,$this->getConfiguration('Nationalité','Nationalité du client',[
                'required' => true,
            ]))
            ->add('ville',TextType::class,$this->getConfiguration('Ville','Ville du client',[
                'required' => true,
            ]))
            ->add('adresse',TextType::class,$this->getConfiguration('Adresse','Adresse du client',[
                'required' => false,
            ]))
//            ->add('observation',TextAreaType::class,$this->getConfiguration('Observation','Observations',[
//                'required' => false,
//            ]))
            ->add('IFU',TextType::class,$this->getConfiguration('IFU','IFU du client',[
                'required' => false,
            ]))
            ->add('dateNaissance',DateType::class,$this->getConfiguration('Date de Naissance','Date de Naissance du client',[
                'required' => true,
            ]))
            ->add('lieuNaissance',TextType::class,$this->getConfiguration('Lieu de Naissance','Lieu de Naissance du client',[
                'required' => true,
            ]))
            ->add('nomJeuneFille',TextType::class,$this->getConfiguration('Nom de jeune fille','Nom de jeune fille du client',[
                'required' => false,
            ]))
            ->add('sexe',ChoiceType::class,$this->getConfiguration('Sexe','Sexe du client',[
                'required' => true,
                'choices' => [
                    'Masculin' => 'Masculin',
                    'Feminin' => 'Féminin',
                ]
            ]))
            ->add('profession',TextType::class,$this->getConfiguration('Profession','Profession du client',[
                'required' => false,
            ]))
            ->add('referenceManuel',TextType::class,$this->getConfiguration('Code ','Code client',[
                'required' => false,
            ]))
            ->add('piece', PieceForm::class,[
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
