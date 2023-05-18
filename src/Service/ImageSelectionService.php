<?php

namespace App\Service;

use App\Entity\Picture;
use App\Entity\PictureOfTheWeek;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ImageSelectionService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function selectImageOfTheWeek(DateTime $startDate, DateTime $endDate): ?Picture
    {
        // Convertir les chaînes de caractères en objets DateTime
        $startOfWeek = DateTime::createFromFormat('Y-m-d H:i:s', $startDate->format('Y-m-d 00:00:00'));
        $endOfWeek = DateTime::createFromFormat('Y-m-d H:i:s', $endDate->format('Y-m-d 23:59:59'));
    
        // Récupérer toutes les images entre les dates spécifiées
        $pictures = $this->entityManager->getRepository(Picture::class)->findPicturesBetweenDates($startOfWeek, $endOfWeek);
    
        // Sélectionner l'image avec le plus de likes
        $selectedPicture = null;
        $maxLikes = 0;
    
        foreach ($pictures as $picture) {
            $likesCount = $picture->getLikes()->count();
    
            if ($likesCount > $maxLikes) {
                $maxLikes = $likesCount;
                $selectedPicture = $picture;
            }
        }
    
        // Créer l'entité PictureOfTheWeek avec l'image sélectionnée
        if ($selectedPicture) {
            $pictureOfTheWeek = new PictureOfTheWeek();
            $pictureOfTheWeek->setTimeStampWeek(new \DateTime());
            $pictureOfTheWeek->addPicture($selectedPicture);
    
            $this->entityManager->persist($pictureOfTheWeek);
            $this->entityManager->flush();
    
            return $selectedPicture;
        }
    
        return null;
    }
    }