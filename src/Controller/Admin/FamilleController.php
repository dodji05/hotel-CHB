<?php

namespace App\Controller\Admin;

use App\Entity\Famille;
use App\Entity\Services;
use App\Form\FamilleType;
use App\Repository\FamilleRepository;
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

        return $this->render('admin/famille/index.html.twig', [
            'famille' => $famille,
            'form' => $form,
            'familles' => $familleRepository->findAll(),
        ]);

    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $famille = new Famille();
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($famille);
            $entityManager->flush();

            return $this->redirectToRoute('app_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('famille/new.html.twig', [
            'famille' => $famille,
            'form' => $form,
        ]);
    }

    #[Route('/{codeFamille}', name: 'show', methods: ['GET'])]
    public function show(Famille $famille): Response
    {
        return $this->render('famille/show.html.twig', [
            'famille' => $famille,
        ]);
    }

    #[Route('/{codeFamille}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Famille $famille, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FamilleType::class, $famille);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_famille_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('famille/edit.html.twig', [
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

        return $this->redirectToRoute('app_famille_index', [], Response::HTTP_SEE_OTHER);
    }
}
