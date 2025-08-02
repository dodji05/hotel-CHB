<?php

namespace App\Controller\Admin;

use App\Entity\GaleriePhotos;
use App\Form\GaleriePhotosType;
use App\Repository\GaleriePhotosRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('administration/galerie-photos')]
class GaleriePhotosController extends AbstractController
{
    #[Route('/', name: 'admin_galerie_photos_index', methods: ['GET'])]
    public function index(GaleriePhotosRepository $galeriePhotosRepository): Response
    {
        return $this->render('admin/galerie/index.html.twig', [
            'photos' => $galeriePhotosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_galerie_photos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FileUploader $fileUploader,GaleriePhotosRepository $galeriePhotosRepository): Response
    {
        $galeriePhoto = new GaleriePhotos();
        $form = $this->createForm(GaleriePhotosType::class, $galeriePhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $annee = $form->get('annee')->getData();
            $images = $form->get('img')->getData();

            foreach ($images as $image) {
                $fichier = $fileUploader->upload($image, 'chb-galerie', 'galerie');
                $photo =  new GaleriePhotos();
                $photo->setUrl($fichier);
                $galeriePhotosRepository->save($photo, true);
                //  $cause->setImages($fichier) ;
            }



            return $this->redirectToRoute('admin_galerie_photos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/galerie/new.html.twig', [
            'galerie_photo' => $galeriePhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_galerie_photos_show', methods: ['GET'])]
    public function show(GaleriePhotos $galeriePhoto): Response
    {
        return $this->render('galerie_photos/show.html.twig', [
            'galerie_photo' => $galeriePhoto,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_galerie_photos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GaleriePhotos $galeriePhoto, GaleriePhotosRepository $galeriePhotosRepository): Response
    {
        $form = $this->createForm(GaleriePhotosType::class, $galeriePhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $galeriePhotosRepository->save($galeriePhoto, true);


            return $this->redirectToRoute('admin_galerie_photos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/galerie/edit.html.twig', [
            'galerie_photo' => $galeriePhoto,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_galerie_photos_delete', methods: ['POST'])]
    public function delete(Request $request, GaleriePhotos $galeriePhoto, GaleriePhotosRepository $galeriePhotosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galeriePhoto->getId(), $request->request->get('_token'))) {
            $galeriePhotosRepository->remove($galeriePhoto, true);
        }
        $this->addFlash('success',"L'image a été supprimé avec success");
        return $this->redirectToRoute('admin_galerie_photos_index', [], Response::HTTP_SEE_OTHER);
    }
}
