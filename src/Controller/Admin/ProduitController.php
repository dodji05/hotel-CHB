<?php

namespace App\Controller\Admin;

use App\Entity\Famille;
use App\Entity\ImagesProduits;
use App\Entity\PrixAAppliquer;
use App\Entity\Produit;
use App\Entity\Services;
use App\Form\ProduitType;
use App\Repository\FamilleRepository;
use App\Repository\PrixAAppliquerRepository;
use App\Repository\ServicesRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\UniqueIdentifierGenerator;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('administration/', name: 'admin_produit_')]
class ProduitController extends AbstractController
{
    #[Route('produit/', name: 'index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, PrixAAppliquerRepository $prixAAppliquerRepository): Response
    {
        $produits = $entityManager
            ->getRepository(Produit::class)
            ->findAll();


        $form = $this->createFormBuilder()
            ->add('service', EntityType::class, [
                'class' => Services::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisissez un service',
                'attr' => ['onchange' => 'this.form.submit();']
            ],
            )
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $codeservice = $form->get('service')->getData();
            return $this->render('admin/produit/pp_index.html.twig', [
                'produits' => $prixAAppliquerRepository->findProduitFamilleByService($codeservice, true),
                'form' => $form->createView(),
            ]);
        }


        return $this->render('admin/produit/index.html.twig', [
            'produits' => $produits,
            'form' => $form->createView(),
        ]);
    }

    #[Route('produit/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UniqueIdentifierGenerator $identifierGenerator, FileUploader $fileUploader): Response
    {
        $produit = new Produit();
        $custonID = $identifierGenerator->generateUniqueIdentifier(Produit::class, 'id', 'P');
        $reference = $identifierGenerator->generateUniqueIdentifier(Produit::class, 'reference', 'RF');
        $produit->setId($custonID);
        $produit->setReference($reference);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $fmane = $form->get('libprod')->getData();
                    $fichier = $fileUploader->upload($image, $fmane, 'produit');
                    $photo = new ImagesProduits();
                    $photo->setProduit($produit);
                    $photo->setUrl($fichier);
                    $entityManager->persist($photo);
                    $produit->setPhoto($fichier);
                }
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

    #[Route('produit/{reference}', name: 'show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin/produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('produit/{reference}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('img')->getData();
            if ($images) {
                foreach ($images as $image) {
                    $fmane = $form->get('libprod')->getData();
                    $fichier = $fileUploader->upload($image, $fmane, 'produit');
                    $photo = new ImagesProduits();
                    $photo->setProduit($produit);
                    $photo->setUrl($fichier);
                    $entityManager->persist($photo);
                   // $produit->setPhoto($fichier);
                }
            }
          //  $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('produit/{reference}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getReference(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('hebergement/', name: 'hebergement', methods: ['GET'])]
    public function hebergement(EntityManagerInterface $entityManager): Response
    {
        // $chambres = $prixAAppliquerRepository->findProduitFamilleByService('S00000011');
        $titre = 'Hebergement';
        $sousTitre1 = 'Chambres';
        $sousTitre2 = 'Liste des chambres';
        $chambres = $entityManager
            ->getRepository(PrixAAppliquer::class)
            ->findProduitFamilleByService('S00000011');

        return $this->render('admin/produit/index_hebergement.html.twig', [
            'chambres' => $chambres,
            'titre' => $titre,
            'sousTitre1' => $sousTitre1,
            'sousTitre2' => $sousTitre2,

        ]);
    }

    #[Route('/mise-en-avant/{reference}', name: 'mise_en_avant', methods: ['GET', 'POST'])]
    public function miseEnAvant(Produit $produit,Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, UniqueIdentifierGenerator $identifierGenerator): Response
    {
        if(!$produit->isFeat()) {
            $produit->setFeat(true);
            $this->addFlash('success', 'Le service a été bien mis en avant!');
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->json([
                'code'=>200,
                'status'=>$produit->isFeat(),
                'message'=>'Le service a été bien mis en avant'
            ]);
        } else {
            $produit->setFeat(false);
            $this->addFlash('success', 'Le service n\' est plus en avant!');
            $entityManager->persist($produit);
            $entityManager->flush();
            return $this->json([
                'code'=>200,
                'status'=>$produit->isFeat(),
                'message'=>'Le service Le service n\' est plus en avant!'
            ]);
        }
    }


}
