<?php

namespace App\Controller\Admin;

use App\Entity\Famille;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\UniqueIdentifierGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('administration/produit', name: 'admin_produit_')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();

        return $this->render('admin/produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UniqueIdentifierGenerator $identifierGenerator, FileUploader $fileUploader): Response
    {
        $produit = new Produit();
        $custonID = $identifierGenerator->generateUniqueIdentifier(Produit::class, 'id','P');
        $reference = $identifierGenerator->generateUniqueIdentifier(Produit::class, 'reference','RF');
        $produit->setId($custonID);
        $produit->setReference($reference);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('libprod')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'produit');      
                $produit->setPhoto($fichier);
            }
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{reference}', name: 'show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{reference}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('libprod')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'produit');      
                $produit->setPhoto($fichier);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{reference}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getReference(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
