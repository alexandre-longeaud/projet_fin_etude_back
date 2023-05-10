<?php

namespace App\DataFixtures;

use App\Entity\Ia;
use App\Entity\Picture;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // * je constitue une liste des IA
        $allIa = [];
        for ($i=0; $i < 5; $i++){
           // 1. créer l'objet
            $newIa = new Ia();
            // 2. on met à jour les propriétés
            $newIa->setName("IA #" . $i);
            $newIa->setLink("https://www.bluewillow.ai/");
            $newIa->setCreatedAt(new DateTime('now'));

            // 3. insertion en BDD
            // * 1. je donne à doctrine mon nouvel objet, pour qu'il en prenne connaissance
            $manager->persist($newIa);
            $allIa[] = $newIa;

        }
        // * je constitue une liste de 30 images
        for ($i=0; $i < 31; $i++){
            // 1. créer un objet Picture
            $newPicture = new Picture();

            // 2. remplire les propriétés de mon nouvel objet
            $newPicture->setPrompt("super prompt #" . $i);
            $newPicture->setUrl("https://www.zupimages.net/up/23/18/8ptc.jpg"); 
            $newPicture->setNbClick(mt_rand(0,99));
            $newPicture->setCreatedAt(new DateTime('now'));

            $randomIndexIa = mt_rand(0, count($allIa)-1);
            $randomIa = $allIa[$randomIndexIa];

            $newPicture->setIa($randomIa);

            $manager->persist($newPicture);

        }

        $manager->flush();
    }
}
