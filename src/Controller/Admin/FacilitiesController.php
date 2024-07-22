<?php

namespace App\Controller\Admin;

use App\Entity\Facilities;
use App\Form\FacilitiesType;
use App\Repository\FacilitiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('Administration/facilities', name: 'admin_facilities_')]
class FacilitiesController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(FacilitiesRepository $facilitiesRepository): Response
    {
        return $this->render('admin/facilities/index.html.twig', [
            'facilities' => $facilitiesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $facility = new Facilities();
        $form = $this->createForm(FacilitiesType::class, $facility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facility);
            $entityManager->flush();

            return $this->redirectToRoute('admin_facilities_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/facilities/new.html.twig', [
            'facility' => $facility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Facilities $facility): Response
    {
        return $this->render('admin/facilities/show.html.twig', [
            'facility' => $facility,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facilities $facility, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FacilitiesType::class, $facility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_facilities_show', ['id'=>$facility->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/facilities/edit.html.twig', [
            'facility' => $facility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Facilities $facility, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facility->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($facility);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_facilities_index', [], Response::HTTP_SEE_OTHER);
    }
}
