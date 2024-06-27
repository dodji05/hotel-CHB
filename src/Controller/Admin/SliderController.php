<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Service\FileUploader;
use App\Repository\SliderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('AdministratioN/slider', name: 'admin_slider_')]
class SliderController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SliderRepository $sliderRepository): Response
    {
        return $this->render('admin/slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager , FileUploader $fileUploader): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($slider);

            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('titre')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'sliders');      
                $slider->setImage($fichier);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Slider $slider): Response
    {
        return $this->render('slider/show.html.twig', [
            'slider' => $slider,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slider $slider, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $images = $form->get('img')->getData();
            if ($images) {
                $fmane = $form->get('titre')->getData();
                $fichier = $fileUploader->upload($images, $fmane, 'sliders');      
                $slider->setImage($fichier);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Slider $slider, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $slider->getId(), $request->request->get('_token'))) {
            $entityManager->remove($slider);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
