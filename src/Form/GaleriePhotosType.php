<?php

namespace App\Form;

use App\Entity\GaleriePhotos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GaleriePhotosType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('annee', TextType::class, $this->getConfiguration("Année :", "année des photos"))
            ->add('img', FileType::class, [
                'label' => 'Ajouter des images',
                'multiple' => true,
                'mapped' => false,
                'required' => true
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GaleriePhotos::class,
        ]);
    }
}
