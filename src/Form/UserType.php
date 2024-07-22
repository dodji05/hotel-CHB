<?php

namespace App\Form;


use App\Entity\User;


use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserType extends ApplicationType
{


    private $security;

    public function __construct(Security $security)
    {

        $this->security = $security;


    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupérez l'utilisateur actuellement connecté
        $user = $this->security->getUser();
        // Les rôles disponibles pour le champ de sélection
        $availableRoles = [];
        // Déterminez si l'utilisateur a un rôle particulier dans un tableau de rôles
        //$hasRole = in_array('ROLE_SUPERADMIN', $user->getRoles());
        $availableRoles = ['ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
            'ROLE_ADMIN' => 'ROLE_ADMIN',
            'ROLE_EDITEUR' => 'ROLE_EDITEUR',

        ];

        $builder
            ->add('email', EmailType::class, $this->getConfiguration('Email:', 'Entrez le mail'))
//            ->add('password', PasswordType::class, $this->getConfiguration('Mot de passe:', 'Entrez  le mot de passe'))
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'type' => PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options' => ['label' => 'Mot de passe'],

                'second_options' => ['label' => 'Saississez a nouveau le mot de passe'],
                'invalid_message' =>'Les deux mots de passe ne concordent pas:',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer le mot de passe :',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'multiple' => true,
                'choices' => $availableRoles,
                'label' => 'Rôle de l\'utilisateur:'
            ])

//            ->add('nom',TextType::class,$this->getConfiguration('Nom:','Entrez le nom'))
//            ->add('prenoms',TextType::class,$this->getConfiguration('Prénom(s):','Entrez les prenoms'))
//            ->add('telephone',TextType::class,$this->getConfiguration('Téléphone:','Entrez le numéro de téléphone'))
//            ->add('poste',TextType::class,$this->getConfiguration('Poste:','Entrez le poste de l\'agent'))

//            ->add('isActif')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
