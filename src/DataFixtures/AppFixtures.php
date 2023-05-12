<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\PictureProvider;
use App\Entity\Ia;
use App\Entity\Picture;
use App\Entity\PictureOfTheWeek;
use App\Entity\Review;
use App\Entity\Role;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;



class AppFixtures extends Fixture
{
    private $connection;


    /**
    * Constructor
    */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * Permet de TRUNCATE les tables et de remettre les AI à 1
     */
    private function truncate()
    {
        // Désactivation la vérification des contraintes FK
        $this->connection->executeQuery('SET foreign_key_checks = 0');
        // On tronque
        $this->connection->executeQuery('TRUNCATE TABLE picture');
        $this->connection->executeQuery('TRUNCATE TABLE ia');
        $this->connection->executeQuery('TRUNCATE TABLE review');
        $this->connection->executeQuery('TRUNCATE TABLE role');
        $this->connection->executeQuery('TRUNCATE TABLE tag');
        $this->connection->executeQuery('TRUNCATE TABLE tag_picture');
        $this->connection->executeQuery('TRUNCATE TABLE user');
        $this->connection->executeQuery('TRUNCATE TABLE picture_of_the_week');
    }


    public function load(ObjectManager $manager): void
    {

        // on tronque les tables
        $this->truncate();





                // liste de Users

            // administrateurs

            $userBen = new User();
            $userBen->setPseudo('Benoit-R');
            $userBen->setMail('benoit@benoit.com');
            $roleAdmin = new Role();
            $roleAdmin->setName('ROLE_ADMIN');
            $roleAdmin->setCreatedAt(new DateTime('now'));
            $userBen->setRole($roleAdmin);
            $userBen->setPassword('$2y$13$QzAdagb9dwOGrkFaVQYYbOzuZypCHfE2bRnx/QTuJqInMrM1JLmaK');
            $userBen->setBio('Benoît Rolet, product owner et administrateur de Maisterpiece.com');
            $userBen->setAvatar('https://ca.slack-edge.com/T051G8W6UAC-U050V5MTX9Q-8c2f44989391-512');
            $userBen->setCreatedAt(new DateTime('now'));
            $manager->persist($roleAdmin);
            $manager->persist($userBen);
    
    
            $userNico = new User();
            $userNico->setPseudo('Nico-C');
            $userNico->setMail('nico@nico.com');
            $roleAdmin->setCreatedAt(new DateTime('now'));
            $userNico->setRole($roleAdmin);
            $userNico->setPassword('$2y$13$pDd02nUT43D6AGyln8D/0eQQdxdSXBMYeOiRMOz2Va.sVYwMssv7u');
            $userNico->setBio('Nicolas Caron, lead developer frontend et administrateur de Maisterpiece.com');
            $userNico->setAvatar('https://ca.slack-edge.com/T051G8W6UAC-U050U6DK5V1-3427f3bfa239-512');
            $userNico->setCreatedAt(new DateTime('now'));
            $manager->persist($roleAdmin);
            $manager->persist($userNico);
    
    
            $userAurelie = new User();
            $userAurelie->setPseudo('Aurelie-S');
            $userAurelie->setMail('aurelie@aurelie.com');
            $roleAdmin->setCreatedAt(new DateTime('now'));
            $userAurelie->setRole($roleAdmin);
            $userAurelie->setPassword('$2y$13$QGA6t.or2IgD6pkdICiSTOorI0bIZUGu1yjntOKuQaCZHlBtzO3b6');
            $userAurelie->setBio('Aurelie Simonneau, Scrum Master et administrateur de Maisterpiece.com');
            $userAurelie->setAvatar('https://ca.slack-edge.com/T051G8W6UAC-U050MEYMPJA-d4f976fc0f58-512');
            $userAurelie->setCreatedAt(new DateTime('now'));
            $manager->persist($roleAdmin);
            $manager->persist($userAurelie);
            
    
            $userAlex = new User();
            $userAlex->setPseudo('Alex-L');
            $userAlex->setMail('alex@alex.com');
            $roleAdmin->setCreatedAt(new DateTime('now'));
            $userAlex->setRole($roleAdmin);
            $userAlex->setPassword('$2y$13$UyNGRncDul0e2mObEzj7gu.4GvtzfEgVWbQj2N.hefBn5pNi3ITE6');
            $userAlex->setBio('Alexandre Longeaud, Git Master et administrateur de Maisterpiece.com');
            $userAlex->setAvatar('https://ca.slack-edge.com/T051G8W6UAC-U050ZEZEE1G-g00bdeff674d-512');
            $userAlex->setCreatedAt(new DateTime('now'));
            $manager->persist($roleAdmin);
            $manager->persist($userAlex);
    
    
            $userChris = new User();
            $userChris->setPseudo('Chris-C');
            $userChris->setMail('christophe@christophe.com');
            $roleAdmin->setCreatedAt(new DateTime('now'));
            $userChris->setRole($roleAdmin);
            $userChris->setPassword('$2y$13$V2rUi5jWZj9Itzi2OcQB3uh6y/D6XXT5nKLvZjybAieihfUNoEYXi');
            $userChris->setBio('Christophe Cumbo, lead developer backend et administrateur de Maisterpiece.com');
            $userChris->setAvatar('https://ca.slack-edge.com/T051G8W6UAC-U051F78D99P-7c135f7c743f-512');
            $userChris->setCreatedAt(new DateTime('now'));
            $manager->persist($roleAdmin);
            $manager->persist($userChris);
    
    
                //utilisateurs membre
    
            $userMembre = new User();
            $userMembre->setPseudo('User-1');
            $userMembre->setMail('user1@user1.com');
            $roleUser = new Role();
            $roleUser->setName('ROLE_USER');
            $roleUser->setCreatedAt(new DateTime('now'));
            $userMembre->setRole($roleUser);
            $userMembre->setPassword('$2y$13$1l2Gv/9G5caLbjuOIz5VCeAYDhZHMM6yFoDFlny0ys2wynA2teh2m');
            $userMembre->setBio('User1, inscrit sur Maisterpiece et membre actif sur le site');
            $userMembre->setAvatar('https://www.zupimages.net/up/23/18/lmmr.jpg');
            $userMembre->setCreatedAt(new DateTime('now'));
            $manager->persist($roleUser);
            $manager->persist($userMembre);
    

            $userMembre2 = new User();
            $userMembre2->setPseudo('User-2');
            $userMembre2->setMail('user2@user2.com');
            $roleUser->setCreatedAt(new DateTime('now'));
            $userMembre2->setRole($roleUser);
            $userMembre2->setPassword('$2y$13$42vU2RXvoHbRaZZ/InyT1.lvkCIF0GAu8BTvQTm6/tj9E4aLsfgtu');
            $userMembre2->setBio('User2, inscrit sur Maisterpiece et membre actif sur le site');
            $userMembre2->setAvatar('https://www.zupimages.net/up/23/18/wasp.jpg');
            $userMembre2->setCreatedAt(new DateTime('now'));
            $manager->persist($roleUser);
            $manager->persist($userMembre2);
            
        // * je constitue une liste des IA
        $allIa = [];
        for ($i=1; $i < 6; $i++) {
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
        for ($i=0; $i < 31; $i++) {
            // 1. créer un objet Picture
            $newPicture = new Picture();
           

            // 2. remplire les propriétés de mon nouvel objet
            $newPicture->setPrompt("mon super prompt #" . $i);
            $pictureIndex = new PictureProvider();
            
            $newPicture->setUrl($pictureIndex->pictureUrl());
            $newPicture->setNbClick(mt_rand(0, 99));
            $newPicture->setCreatedAt(new DateTime('now'));
            $newPicture->setUser($userMembre);

            $randomIndexIa = mt_rand(0, count($allIa)-1);
            $randomIa = $allIa[$randomIndexIa];

            $newPicture->setIa($randomIa);
            $newReview = new Review();

            $newReview->setContent("super image #" . $i);
            $newReview->setUser($userMembre);

            $newReview->setCreatedAt(new DateTime('now'));
            $newPicture->addReview($newReview);
            $manager->persist($newReview);
            $manager->persist($newPicture);
        }


        $newPictureOfTheWeek = new Picture();
        $newPictureOfTheWeek->setPrompt("mon super prompt!");
        $newPictureOfTheWeek->setUrl("https://www.zupimages.net/up/23/18/qs7v.jpg");
        $newPictureOfTheWeek->setNbClick(mt_rand(20, 99));
        $newPictureOfTheWeek->setCreatedAt(new DateTime('now'));
        $newPictureOfTheWeek->setUser($userMembre2);
        $pictureOfTheWeek = new PictureOfTheWeek();
        $pictureOfTheWeek->addPicture($newPictureOfTheWeek);
        $pictureOfTheWeek->setTimeStampWeek(new DateTime('now'));
        $manager->persist($newPictureOfTheWeek);
        $manager->persist($pictureOfTheWeek);
    




            






        $manager->flush();
    }
}
