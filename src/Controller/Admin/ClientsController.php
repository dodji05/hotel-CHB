<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('administration')]
class ClientsController extends AbstractController
{
    #[Route('/clients', name: 'admin_clients_index')]
    public function listeClients(ClientRepository $clientRepository){
        $clients = $clientRepository->findAll();
        return $this->render('admin/clients/liste_clients.html.twig', [
            'clients' => $clients
        ]);

    }
}
