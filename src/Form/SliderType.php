<?php

namespace App\Form;

use App\Entity\Slider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SliderType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('img', FileType::class, [
                'label' => "Images du slider",
                'multiple' => false,
                'mapped' => false,
                'required' => false,

            ])
            ->add('titre')
//            ->add('btn', TextType::class, $this->getConfiguration("Libelle du bouttoun", "Entrez le texte qui s'affichera sur le bouton", [
//                'required' => false
//            ]))
//            ->add('lien', TextType::class, $this->getConfiguration("Lien", "", [
//                'required' => false
//            ]))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slider::class,
        ]);
    }
}
