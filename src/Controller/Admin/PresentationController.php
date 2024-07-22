<?php

namespace App\Controller\Admin;

use App\Entity\Presentation;
use App\Form\PresentationType;
use App\Repository\PresentationRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('Administration/presentation', name: 'admin_presentation_')]
class PresentationController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PresentationRepository $presentationRepository): Response
    {
        return $this->render('admin/presentation/index.html.twig', [
            'presentations' => $presentationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $presentation = new Presentation();

        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($presentation);
            $entityManager->flush();

            return $this->redirectToRoute('admin_presentation_edit', ['id'=>$presentation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/presentation/new.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_presentation_show', methods: ['GET'])]
    public function show(Presentation $presentation): Response
    {
        return $this->render('Admin/presentation/show.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, PresentationRepository $presentationRepository,FileUploader $fileUploader): Response
    {
        $presentation = $presentationRepository->find($request->get('id'));
        if ($presentation ==null){
            return $this->redirectToRoute('admin_presentation_new');
        }
        $form = $this->createForm(PresentationType::class, $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imagesAlaUne = $form->get('img')->getData();
            if( $imagesAlaUne){
                $fichier = $fileUploader->upload($imagesAlaUne, null, 'entreprise');
                $presentation->setPhoto( $fichier )    ;
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_presentation_edit', ['id'=>$presentation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/presentation/edit.html.twig', [
            'presentation' => $presentation,
            'form' => $form,
        ]);
    }

//    #[Route('/{id}', name: 'app_presentation_delete', methods: ['POST'])]
//    public function delete(Request $request, Presentation $presentation, EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$presentation->getId(), $request->getPayload()->getString('_token'))) {
//            $entityManager->remove($presentation);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('app_presentation_index', [], Response::HTTP_SEE_OTHER);
//    }

    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Presentation $presentation, Request $request, EntityManagerInterface $em, FileUploader $fileUploader): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $presentation->getId(), $data['_token'])) {
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom =$presentation->getPhoto();

            if ($fileUploader->remove($nom)) {
                // On supprime l'image de la base de données
                $presentation->setPhoto(null);
                $em->persist($presentation);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
