<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Services;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\ServicesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('administration/statistiques')]
class StaticController extends AbstractController
{
    #[Route('/', name: 'admin_statistique')]
    public function index(Request $request,ServicesRepository $servicesRepository): Response
    {
        $caServices = $servicesRepository->CAParService();
        $form = $this->createFormBuilder()
            ->add('service', EntityType::class, [
                'class' => Services::class,
                'choice_label' => 'libelle',
                'placeholder' => 'Choisissez un service',
                'attr' => ['onchange' => 'this.form.submit();']
            ],
            )
            ->add('dateDebut', DateType::class, [
                'placeholder' => 'dd/mm/yyyy',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('dateFin', DateType::class, [
                'placeholder' => 'dd/mm/yyyy',
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $codeservice = $form->get('service')->getData();
            $debut = $form->get('dateDebut')->getData();
            $fin = $form->get('dateFin')->getData();
            $servis = $servicesRepository->CAParServicePeriode($codeservice,$debut->format('Y-m-d'), $fin->format('Y-m-d') );
          //  dd(  $codeservice,$debut->format('Y-m-d'),$fin->format('Y-m-d'),$servis);
//            return $this->render('admin/produit/pp_index.html.twig', [
//                'produits' =>  $prixAAppliquerRepository->findProduitFamilleByService($codeservice),
//                'form' => $form->createView(),
//            ]);
            return $this->render('admin/statistique/index.html.twig', [
                'form' => $form->createView(),
                'service' => $servicesRepository->findOneBy(['codeService' => $codeservice]),
                'type'=>2,
                'debut'=>$debut,
                'fin'=>$fin,
                'caParServices' =>$servis,
            ]);
        }

        $servi = $servicesRepository->getServiceCa();

        //  dd( $servis,new \DateTime('2024-01-01'),new \DateTime('2024-12-31'));

        return $this->render('admin/statistique/index.html.twig', [
            'form' => $form->createView(),
            'caParServices' => $caServices,
            'type'=>1,
            'servi' => $servi,
        ]);
    }

    #[Route('/chiffres-affaires-services')]
    public function chiffresAffairesParServices(ServicesRepository $servicesRepository): Response
    {
        $paramDateFacture = new \DateTime('2024-01-01');
        $paramDateFacture1 = new \DateTime('2024-12-31');
        $paramCodeServices = 'S00000011';
//        $ca = $servicesRepository->chiffresAffairesParServices($paramDateFacture ,$paramDateFacture1,$paramCodeServices);
        $ca = $servicesRepository->CA();
        dd($ca);
        return $this->render('static/index.html.twig');
    }

    #[Route('/ventes-par-peridodes')]
    public function venteParPeriode(FactureRepository $factureRepository): Response
    {
        $paramDateFacture = new \DateTime('2024-01-01');
        $paramDateFacture1 = new \DateTime('2024-12-31');
        $paramCodeServices = 'S00000001';
//        $ca = $servicesRepository->ffff($paramDateFacture ,$paramDateFacture1,$paramCodeServices);
        //  $ca =$factureRepository->venteParPeriode($paramCodeFamille_pr,$paramDateFacture,$paramDateFacture1);
        dd($ca);
        return $this->render('static/index.html.twig');
    }

    #[Route('/meilleurs-clients')]
    public function meilleursClients(ClientRepository $clientRepository): Response
    {
        $paramDateFacture = new \DateTime('2024-01-01');
        $paramDateFacture1 = new \DateTime('2024-12-31');
        $paramCodeServices = 'S00000001';
//        $ca = $servicesRepository->ffff($paramDateFacture ,$paramDateFacture1,$paramCodeServices);
        $ca = $clientRepository->topClient($paramDateFacture, $paramDateFacture1);
        dd($ca);
        return $this->render('static/index.html.twig');
    }
}
