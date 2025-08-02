<?php

namespace App\Form;

use App\Entity\Famille;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libprod', TextType::class, $this->getConfiguration("Libelle du produit", "Saisissez le Libelle du produit", [
                'required' => false
            ]))
            ->add('CodeFamille', EntityType::class, [
                'class' => Famille::class,
                'choice_label' => 'libelle',
                'placeholder'=>'Selectionner la famille',
                'label' => 'Famille',
            ],
            )
//            ->add('prixrevient')
      //      ->add('prixht')
//            ->add('qtereappro')
//            ->add('qtemini')
//            ->add('tauxtva')
//            ->add('gencode')
//            ->add('codebarre')
//            ->add('saisipar')
//            ->add('saisile', null, [
//                'widget' => 'single_text',
//            ])

//            ->add('aib')
//            ->add('stockactuel')
//            ->add('id')
//            ->add('profamille')

            ->add('description',TextareaType::class,[
                'required' => false,
            ])
//            ->add('assujettitva')
//            ->add('assujettiaib')
//            ->add('idsociete')
//            ->add('idannee')
//            ->add('referenceinexistante')
//            ->add('codebare')
//            ->add('qteappro')
//            ->add('qtevente')
//            ->add('qterebus')
//            ->add('libfamille')
            //        ->add('libprodv')

//            ->add('marge')
//            ->add('nimFacturePreuve')
//            ->add('signatureFacurePreuve')
//            ->add('ptvaMarge')
//            ->add('ptvaHt')
//            ->add('estmatierespremiere')
            ->add('eststockable')
//            ->add('estdisponible')


            ->add('img', FileType::class, [
                'label' => "Images du produit",
                'multiple' => true,
                'mapped' => false,
                'required' => false,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
