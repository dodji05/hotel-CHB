<?php

namespace App\Controller;

use App\Entity\PrixAAppliquer;
use App\Form\PrixAAppliquerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prix/a/appliquer')]
class PrixAAppliquerController extends AbstractController
{
    #[Route('/', name: 'app_prix_a_appliquer_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $prixAAppliquers = $entityManager->getRepository(PrixAAppliquer::class)->findAll();

        return $this->render('prix_a_appliquer/index.html.twig', [
            'prix_a_appliquers' => $prixAAppliquers,
        ]);
    }

    #[Route('/new', name: 'app_prix_a_appliquer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prixAAppliquer = new PrixAAppliquer();
        $form = $this->createForm(PrixAAppliquerType::class, $prixAAppliquer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prixAAppliquer);
            $entityManager->flush();

            return $this->redirectToRoute('app_prix_a_appliquer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prix_a_appliquer/new.html.twig', [
            'prix_a_appliquer' => $prixAAppliquer,
            'form' => $form,
        ]);
    }

    #[Route('/{codeprixApplique}', name: 'app_prix_a_appliquer_show', methods: ['GET'])]
    public function show(PrixAAppliquer $prixAAppliquer): Response
    {
        return $this->render('prix_a_appliquer/show.html.twig', [
            'prix_a_appliquer' => $prixAAppliquer,
        ]);
    }

    #[Route('/{codeprixApplique}/edit', name: 'app_prix_a_appliquer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PrixAAppliquer $prixAAppliquer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrixAAppliquerType::class, $prixAAppliquer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prix_a_appliquer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prix_a_appliquer/edit.html.twig', [
            'prix_a_appliquer' => $prixAAppliquer,
            'form' => $form,
        ]);
    }

    #[Route('/{codeprixApplique}', name: 'app_prix_a_appliquer_delete', methods: ['POST'])]
    public function delete(Request $request, PrixAAppliquer $prixAAppliquer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prixAAppliquer->getCodeprixApplique(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prixAAppliquer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prix_a_appliquer_index', [], Response::HTTP_SEE_OTHER);
    }
}
