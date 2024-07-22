<?php

namespace App\Controller\Admin;

use App\Entity\Famille;
use App\Entity\Services;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
use App\Service\FileUploader;
use App\Service\UniqueIdentifierGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('AdministratioN/famille', name: 'admin_famille_')]
class FamilleController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request, FamilleRepository $familleRepository, EntityManagerInterface $entityManager,UniqueIdentifierGenerator $identifierGenerator): Response
    {
        $famille = new Famille();

        $custonID = $identifierGenerator->generateUniqueIdentifier(Famille::class, 'codeFamille','F');
        $famille->setCodeFamille($custonID);
//        dd($custonID);
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($famille);
            $entityManager->flush();

            return $this->redirectToRoute('admin_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/produit/type_hebergement.html.twig', [
            'famille' => $famille,
            'form' => $form,
            'familles' => $familleRepository->findAll(),
            'nature'=>'famille'
        ]);

    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,FileUploader $fileUploader): Response
    {
        $famille = new Famille();
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('libelle')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'famille');
                $famille->setUrlImage($fichier);
            }
            $entityManager->persist($famille);
            $entityManager->flush();

            return $this->redirectToRoute('admin_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/famille/new.html.twig', [
            'famille' => $famille,
            'form' => $form,
            'libelle'=>"Ajouter une famille",

        ]);
    }

    #[Route('/{codeFamille}', name: 'show', methods: ['GET'])]
    public function show(Famille $famille): Response
    {
        return $this->render('admin/famille/show.html.twig', [
            'famille' => $famille,
        ]);
    }

    #[Route('/{codeFamille}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Famille $famille, EntityManagerInterface $entityManager,FileUploader $fileUploader): Response
    {
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('libelle')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'famille');
                $famille->setUrlImage($fichier);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/famille/edit.html.twig', [
            'famille' => $famille,
            'form' => $form,
        ]);
    }

    #[Route('/{codeFamille}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Famille $famille, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$famille->getCodeFamille(), $request->request->get('_token'))) {
            $entityManager->remove($famille);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_famille_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('type-hebergement/', name: 'type_hebergement', methods: ['GET'])]
    public function typeHebergement( FamilleRepository $familleRepository){
        $typesHebergement = $familleRepository->findBy(['numero'=>3]);

        return $this->render('admin/produit/type_hebergement.html.twig', [
            'familles' => $typesHebergement,
            'nature'=>'hebergement'
//            'form' => $form,
//            'familles' => $familleRepository->findAll(),
        ]);

    }

    #[Route('famille/type-hebergement/', name: 'new_type_hebergement', methods: ['GET', 'POST'])]
    public function typeHebergementFamille( Request $request,FamilleRepository $familleRepository,EntityManagerInterface $entityManager,FileUploader $fileUploader,UniqueIdentifierGenerator $identifierGenerator){
        $famille = new Famille();
        $custonID = $identifierGenerator->generateUniqueIdentifier(Famille::class, 'codeFamille','TA');
        $famille->setCodeFamille($custonID);
//
        $famille->setNumero(3);
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('libelle')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'famille');
                $famille->setUrlImage($fichier);
            }
            $entityManager->persist($famille);
            $entityManager->flush();

            return $this->redirectToRoute('admin_famille_type_hebergement', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('admin/famille/new.html.twig', [
            'famille' => $famille,
            'form' => $form,
            'libelle'=>"Ajouter un type d'hébergement",
            'nature'=>'hebergement'
        ]);
    }
}
