<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="app_api_home")
 */
class PictureController extends AbstractController
{
  
     /**
     * Affiche les 30 images les plus récente en page d'accueil / Display the 30 most recents pictures on homepage
     * 
     * @Route("/pictures/home", name="app_api_picture_browseByCreatedAt", methods={"GET"})
     */
    public function browseByCreatedAt(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }

     /**
     * Affiche l'image selectionnée / Display the selected picture
     * 
     * @Route("/pictures/{id}", name="app_api_picture_read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, PictureRepository $pictureRepository): JsonResponse
    {
        $picture = $pictureRepository->find($id);

        if ($picture === null){return $this->json("image inexistant",Response::HTTP_NOT_FOUND);}

        return $this->json($picture, 200, [], ["groups"=>"picture"]);
    }

      /**
     * Affiche les 30 images les plus likées / Display the 30 most liked pictures
     * 
     * @Route("/pictures/liked", name="app_api_picture_browseMostLiked", methods={"GET"})
     */
    public function browseMostLiked(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }

      /**
     * Affiche les 30 images les plus regardées/cliquées / Display the 30 most clicked pictures
     * 
     * @Route("/pictures/clicked", name="app_api_picture_browseMostClicked", methods={"GET"})
     */
    public function browseMostClicked(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }

     /**
     * Affiche les 30 images les plus commentées / Display the 30 most commented pictures
     * 
     * @Route("/pictures/reviewed", name="app_api_pictures_browseMostReviewed", methods={"GET"})
     */
    public function browseMostReviewed(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }

     /**
     * Affiche les 30 images par IA / Display the 30 last pictures by AI
     * @Route("/pictures/ia", name="app_api_pictures_browseMostByAi", methods={"GET"})
     */
    public function browseMostByAi(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }

     /**
     * Affiche toute les images d'un utilisateur / Display all the pictures from an user
     * @Route("/pictures/user/list", name="app_api_pictures_browsePicturesUser", methods={"GET"})
     */
    public function browsePicturesUser(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }



}
