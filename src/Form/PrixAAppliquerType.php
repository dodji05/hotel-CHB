<?php

namespace App\Form;

use App\Entity\PrixAAppliquer;
use App\Entity\Produit;
use App\Entity\Services;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixAAppliquerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeprixApplique')
           
            ->add('prix')
            ->add('ID', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'id',
            ])
            ->add('codeservice', EntityType::class, [
                'class' => Services::class,
                'choice_label' => 'libelle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PrixAAppliquer::class,
        ]);
    }
}
