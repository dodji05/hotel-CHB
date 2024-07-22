<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AjaxController extends AbstractController
{
    #[Route('/ajax')]
    public function index(): Response
    {
        return $this->render('ajax/index.html.twig');
    }
}
