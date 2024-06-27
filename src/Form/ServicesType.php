<?php

namespace App\Form;

use App\Entity\Services;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServicesType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           // ->add('codeService')
            ->add('libelle', TextType::class, $this->getConfiguration("Nom du service :", "Entrez le nom du service", ['required' => true]))
            ->add('description', TextareaType::class, $this->getConfiguration("Description du service :", "Entrez une description du service ", ['required' => true]))
            ->add('img', FileType::class, [
                'label' => "Images du service",
                'multiple' => false,
                'mapped' => false,
                'required' => false,

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Services::class,
        ]);
    }
}
