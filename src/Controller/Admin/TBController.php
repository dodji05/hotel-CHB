<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('administration')]
class TBController extends AbstractController
{
    #[Route('/', name: 'admin_TB_index', methods: ['GET'])]
    public function index()
    {
        return $this->render('admin/base_admin.html.twig', [
//            'familles' => $familleRepository->findAll(),
        ]);
    }
}
