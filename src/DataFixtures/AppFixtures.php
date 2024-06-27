<?php

namespace App\DataFixtures;

use Faker\Factory;

use App\Entity\Famille;
use App\Entity\Produit;

use Mmo\Faker\PicsumProvider;
use App\Entity\PrixAAppliquer;
use App\Entity\Services;
use Doctrine\Persistence\ObjectManager;
use App\Service\UniqueIdentifierGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordEncoder,
    private UniqueIdentifierGenerator $identifierGenerator
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->faker->addProvider(new PicsumProvider( $this->faker));
//        $faker = Faker\Factory::create('fr_FR');
//        $faker->addProvider(new Image($faker));
// $custonID = $this->identifierGenerator->generateUniqueIdentifier(Famille::class, 'codeFamille','F');
// $id[]= 0;       
// for ($i = 1; $i <= 7; $i++) {
//             $famille = new Famille();
//             $famille->setCodeFamille($custonID);
//             $famille->setNumero($this->faker->randomElement([0, 0,3,5]));
//             $famille->setLibelle($this->faker->words(3, true)); 
//              $manager->persist($famille);
//              $manager->flush();
//             $custonID = $this->identifierGenerator->generateUniqueIdentifier(Famille::class, 'codeFamille','F');
            
//         }
      
        

        for ($i = 1; $i <= 87; $i++) {
            $produit = new Produit();
            $custonID = $this->identifierGenerator->generateUniqueIdentifier(Produit::class, 'id','P');
            $reference = $this->identifierGenerator->generateUniqueIdentifier(Produit::class, 'reference','RF');
           
            $produit->setID($custonID);
            $produit->setReference($reference);
            $produit->setLibprod($this->faker->words(2,true));
            $randomFamille= $manager->getRepository(Famille::class)->findBy([], ['codeFamille' => 'ASC']);
            $produit->setCodeFamille($this->faker->randomElement( $randomFamille));
            $prixHt = $this->faker->randomNumber(6);
            $produit->setPrixht( $prixHt);$produit->setPrixrevient((1 + 0.15)*$prixHt);
            $produit->setPhoto( $this->faker->picsumStaticRandomUrl(926, 756));
            $produit->setPrixrevient((1 + 0.15)*$prixHt);
            $manager->persist($produit);
            $manager->flush();

            $custonID = $this->identifierGenerator->generateUniqueIdentifier(Produit::class, 'id','P');
            $reference = $this->identifierGenerator->generateUniqueIdentifier(Produit::class, 'reference','RF');
           
        }

       
        $randomProduit=null;
        for ($i = 1; $i <= 278; $i++) {
            $pp = new PrixAAppliquer();
            $custonID = $this->identifierGenerator->generateUniqueIdentifier(PrixAAppliquer::class, 'codeprixApplique','PA');
          
            $randomSerivces= $manager->getRepository(Services::class)->findBy([], ['codeService' => 'ASC']);
            $randomProduit= $manager->getRepository(Produit::class)->findBy([], ['id' => 'ASC']);           
            $pp->setCodeprixApplique($custonID);
          
            $service =  $this->faker->randomElement($randomSerivces);
            //$services[] = $service;
            $pp->setCodeservice($service);      
            $pdt = $this->faker->randomElement( $randomProduit);
            $pp->setId( $pdt);
            $taux = $this->faker->randomElement([1.05, 1.15, 1.25, 1.5, 1.1]);
            
            $pp->setPrix($taux * $pdt->getPrixrevient());
            $manager->persist($pp);
            $manager->flush();

            $custonID = $this->identifierGenerator->generateUniqueIdentifier(PrixAAppliquer::class, 'codeprixApplique','PA');

        }
// dd(  $randomProduit);
       
    }
}
