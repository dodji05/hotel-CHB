<?php

namespace App\Controller\Admin;

use App\Entity\PasswordUpdateProfile;
use App\Entity\PaswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\PasswordUpdateUserType;
use App\Form\UpdateUserFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\HttpFoundation\getUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


#[Route('administration/utilisateur')]


class UserController extends AbstractController
{
    #[Route('/', name: 'admin_user_index', methods: ['GET'])]
//    #[IsGranted('ROLE_SUPER_ADMIN')]
//    #[Security("is_granted('ROLE_SUPER_ADMIN')  ||  is_granted('ROLE_BASE_ADMIN')  ||  is_granted('ROLE_OR_ADMIN')  ||  is_granted('ROLE_DIAMANT_ADMIN')")]
    public function index(UserRepository $userRepository): Response
    {

        // dd($entreprise);
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
//    #[Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_BASE_ADMIN') or is_granted('ROLE_OR_ADMIN') or is_granted('ROLE_DIAMANT_ADMIN')")]
//    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function new(Request $request,  UserPasswordHasherInterface $userPasswordHasher ,UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
//            $userRepository->save($user, true);

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
    #[IsGranted('ROLE_SUPER_ADMIN')]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/account/profile-update', name: 'account_profile_update_by_user', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_USER')")]
    public function profile(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader, UserRepository $userRepository, UserPasswordHasherInterface $encoder)
    {
        $user = $this->getUser();
        // dd($user);
        $passwordUpdate = new PasswordUpdateProfile();


        // dd($user);

        $form = $this->createForm(UpdateUserFormType::class, $user);
        $form_reset = $this->createForm(PasswordUpdateUserType::class);

        $form->handleRequest($request);
        // Mise à jour photo


        //fin
        ///dd($fichier,$user);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('img')->getData()) {

                $image = $form->get('img')->getData();
                $fichier = $fileUploader->upload($image);
                $user->setPhoto($fichier);
                $manager->persist($user);
            }
            // dd($user);
            $manager->flush();
            // $userRepository->save($user, true);
            $this->addFlash(
                'success',
                "Les données du profil ont été modifiées avec succès !"
            );

            return $this->redirectToRoute('account_profile_update_by_user');

        }


        $form_reset->handleRequest($request);
        if ($form_reset->isSubmitted() && $form_reset->isValid()) {

            //dd($form_reset->get('oldPassword'));
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if (!password_verify($form_reset->get('oldPassword')->getData(), $user->getPassword())) {


                //dd(!password_verify($passwordUpdate->getOldPassword(), $user->getPassword()));
                // Gérer l'erreur
                $form_reset->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $form_reset->get('newPassword')->getData();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                if ($user->getPasswordChangeRequired() == true) {
                    $user->setPasswordChangeRequired(false);
                }
                $user->setPasswordChangedAt(new \DateTime());


                $userRepository->save($user, true);


                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );


                return $this->redirectToRoute('app_login');
            }
        }
        $passwordChangedAt = $user->getPasswordChangedAt();
        $now = new \DateTime();
        $interval = $now->diff($passwordChangedAt);
        $daysSinceLastChange = $interval->days;
        return $this->render('user/update-profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'form_reset' => $form_reset->createView(),
            'daysSinceLastChange' => $daysSinceLastChange,

        ]);


    }


    #[Route('/account/password-update/by-user', name: 'account_password_user', methods: ['GET', 'POST'])]
  //  #[Security("is_granted('ROLE_USER')")]
    public function updatePasswordByUser(Request $request, UserPasswordHasherInterface $encoder, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $passwordUpdate = new PasswordUpdateProfile();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateUserType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setPassword($hash);

                $entityManager->persist($user);
                $entityManager->flush();
//                $userRepository->save($user, true);

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('app_login');
            }
        }


        return $this->render('admin/user/passwordupdatebyuser.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/account/password-update', name: 'reset_password_by_admin', methods: ['GET', 'POST'])]
    #[Security("is_granted('ROLE_SUPER_ADMIN') or is_granted('ROLE_BASE_ADMIN') or is_granted('ROLE_OR_ADMIN') or is_granted('ROLE_DIAMANT_ADMIN')")]
    public function updatePassword(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $encoder, EntityManagerInterface $manager)
    {
        $passwordUpdate = new PaswordUpdate();
        $user = $userRepository->findOneBy([
            'id' => $user->getId()
        ]);

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $encoder->hashPassword(
                    $user,
                    $form->get('newPassword')->getData()
                )
            );

            $newPassword = $passwordUpdate->getNewPassword();
            $hash = $encoder->hashPassword($user, $newPassword);

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "le mot de passe" . ' ' . $user->getFullName() . " a bien été reinitialisé !"
            );

            return $this->redirectToRoute('app_user_index');

        }


        return $this->render('user/passwordupdatebyAdmin.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
    #[Route('/password/password-change/by-user', name: 'account_password_change_user', methods: ['GET', 'POST'])]
    //  #[Security("is_granted('ROLE_USER')")]
    public function changePasswordByUser(Request $request, UserPasswordHasherInterface $encoder, UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $passwordUpdate = new PasswordUpdateProfile();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateUserType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Vérifier que le oldPassword du formulaire soit le même que le password de l'user
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // Gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                $user->setPasswordChangeRequired(false);

                $entityManager->persist($user);
                $entityManager->flush();
//                $userRepository->save($user, true);

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('app_login');
            }
        }


        return $this->render('admin/user/changepasswordupdatebyuser.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
