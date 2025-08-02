<?php


namespace App\Service;


use App\Entity\PrixAApplique;
use App\Entity\PrixAAppliquer;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    public function __construct(private RequestStack $requestStack,)
    {

    }

    public function ajout(PrixAApplique $prixAApplique, $quantite=null)
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('chb_panier', []);
//        $typepanier = $session->get('chb_panier_type');
        $quantite = $session->get('chb_nb_jour');
        $id = (string)$prixAApplique->getProduit()->getId();
////        //dd($id);
//        if (empty($panier[$id])) {
//            $panier[$id] = 1;
//        } else {
//            $panier[$id]++;
//        }
            if ($quantite==null){
                $qte = 1;

                $type ='hotel';
            } else{
                $qte = $quantite;
                $type ='hotel';
            }
        $session->set('chb_panier_type', $type);
        // Ajouter une qtity +1 à mon produit
        if (isset($panier[$id])) {
            $panier[$id] = [
                'object' => $prixAApplique,
                'quantite' => $panier[$id]['quantite'] + 1,
                'produit' => $prixAApplique->getProduit()->getLibelle(),
//                'image' => $prixAApplique->getProduit()->getImageProduit(),
                'prix' => $prixAApplique->getPrix(),

            ];
        } else {
            $panier[$id] = [
                'object' => $prixAApplique,
                'quantite' =>  $qte,
                'produit' => $prixAApplique->getProduit()->getLibelle(),
//                'image' => $prixAApplique->getProduit()->getImageProduit(),
                'prix' => $prixAApplique->getPrix(),

            ];
        }
        $session->set('chb_panier', $panier);
//        $session->set('chb_panier_type', $type);
//         dd( $session->get('chb_panier'),$panier);
    }


    /*
      * supprimer()
      * Supprimer totalement le panier
      */
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    public function getTotalWt()
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('chb_panier');
        $price = 0;

        if (!isset($cart)) {
            return $price;
        }

        foreach ($cart as $product) {
            $price = $price + ($product['object']->getPrix() * $product['quantite']);
        }

        return $price;
    }

    public function dimunier(PrixAAppliquer $prixAAppliquer)
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('chb_panier', []);
        $id = $prixAAppliquer->getCodeprixApplique();
        //dd($id);
        if ($panier[$id]['quantite'] > 1) {
            $panier[$id]['quantite']--;
        } else {
            unset($panier[$id]);
        }
        $session->set('chb_panier', $panier);
    }

    public function supprimer(PrixAApplique $prixAApplique)
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('chb_panier', []);
        $id = (string)$prixAApplique->getId();
        //dd($id);
        if ($panier[$id]) {
            unset($panier[$id]);
        }
        $session->set('chb_panier', $panier);
    }
}
