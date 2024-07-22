<?php

namespace App\Controller\Admin;

use App\Entity\Services;
use App\Form\ServicesType;
use App\Service\FileUploader;
use App\Service\UniqueIdentifierGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('AdministratioN/services', name: 'admin_services_')]
class ServicesController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $titre = 'Services';
        $sousTitre1 = 'Services';
        $sousTitre2 = 'Liste des services';
        $services = $entityManager
            ->getRepository(Services::class)
            ->findAll();

        return $this->render('admin/services/index.html.twig', [
            'services' => $services,
            'titre' => $titre,
            'sousTitre1' => $sousTitre1,
            'sousTitre2' => $sousTitre2
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader, UniqueIdentifierGenerator $identifierGenerator): Response
    {
        $titre = 'Services';
        $sousTitre1 = 'Services';
        $sousTitre2 = 'Nouveau';
        $service = new Services();
        $custonID = $identifierGenerator->generateUniqueIdentifier(Services::class, 'codeService', 'S');
        $service->setCodeService($custonID);

        $form = $this->createForm(ServicesType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $imagesAlaUne = $form->get('img')->getData();
            if ($imagesAlaUne) {
                $fichier = $fileUploader->upload($imagesAlaUne, $form->get('libelle')->getData(), 'services');
                $service->setImage($fichier);
            }
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('admin_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/services/new.html.twig', [
            'service' => $service,
            'form' => $form,
            'titre' => $titre,
            'sousTitre1' => $sousTitre1,
            'sousTitre2' => $sousTitre2
        ]);
    }

    #[Route('/{codeService}', name: 'show', methods: ['GET'])]
    public function show(Services $service): Response
    {
        $titre = 'Services';
        $sousTitre1 = 'Services';
        $sousTitre2 = 'Fiche service';
        return $this->render('admin/services/show.html.twig', [
            'service' => $service,
            'titre' => $titre,
            'sousTitre1' => $sousTitre1,
            'sousTitre2' => $sousTitre2
        ]);
    }

    #[Route('/{codeService}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Services $service, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $titre = 'Services';
        $sousTitre1 = 'Services';
        $sousTitre2 = 'modification';
        $form = $this->createForm(ServicesType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagesAlaUne = $form->get('img')->getData();
            if ($imagesAlaUne) {
                $fichier = $fileUploader->upload($imagesAlaUne, $form->get('libelle')->getData(), 'services');
                $service->setImage($fichier);
            }
            $entityManager->flush();

            return $this->redirectToRoute('admin_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/services/edit.html.twig', [
            'service' => $service,
            'form' => $form,
            'titre' => $titre,
            'sousTitre1' => $sousTitre1,
            'sousTitre2' => $sousTitre2
        ]);
    }

    #[Route('/{codeService}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Services $service, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $service->getCodeService(), $request->request->get('_token'))) {
            $entityManager->remove($service);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_services_index', [], Response::HTTP_SEE_OTHER);
    }
}
