<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api")
 */
class PictureController extends AbstractController
{

    /**********************************************************************************************************************************************************************************************************
                                                                                            PAGE ACCUEIL/HOMEPAGE
     **********************************************************************************************************************************************************************************************************/
  
     /**
     * Affiche les 30 images les plus récente en page d'accueil / Display the 30 most recents pictures on homepage
     * 
     * @Route("/pictures", name="app_api_pictures_browseByCreatedAt", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function browseByCreatedAt(PictureRepository $pictureRepository): JsonResponse
    {
        dd($this->getUser());
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>["picture"]]);
    }

    /**
     * Affiche l'image de la semaine/ Display picture of the week
     * 
     * @Route("/pictures/week", name="app_api_pictures_browsePictureWeek", methods={"GET"})
     */
    public function browsePictureWeek(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesOfTheWeek = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesOfTheWeek, 200, [],["groups"=>["picture"]]);
    }


     /**
     * Affiche l'image selectionnée / Display the selected picture
     * 
     * @Route("/pictures/{id}", name="app_api_pictures_read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, PictureRepository $pictureRepository): JsonResponse
    {
        $picture = $pictureRepository->find($id);

        if ($picture === null){return $this->json("image inexistant",Response::HTTP_NOT_FOUND);}

        return $this->json($picture, 200, [], ["groups"=>["picture"]]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       FILTRES PAGE ACCUEIL/FILTER HOMEPAGE                                                                          
     **********************************************************************************************************************************************************************************************************/

      /**
     * Affiche les 30 images les plus likées / Display the 30 most liked pictures
     * 
     * @Route("/pictures/filtre/liked", name="app_api_picture_browseMostLiked", methods={"GET"})
     */
    public function browseMostLiked(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesLiked = $pictureRepository->findPictureByLikes();
        return $this->json($picturesLiked, 200, [],["groups"=>["picture"]]);
    }

      /**
     * Affiche les 30 images les plus regardées/cliquées / Display the 30 most clicked pictures
     * 
     * @Route("/pictures/filtre/clicked", name="app_api_picture_browseMostClicked", methods={"GET"})
     */
    public function browseMostClicked(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesClicked = $pictureRepository->findPicturerByNbClic();
        return $this->json($picturesClicked, 200, [],["groups"=>["picture"]]);
    }

     /**
     * Affiche les 30 images les plus commentées / Display the 30 most commented pictures
     * 
     * @Route("/pictures/filtre/reviewed", name="app_api_pictures_browseMostReviewed", methods={"GET"})
     */
    public function browseMostReviewed(PictureRepository $pictureRepository): JsonResponse
    {
    $pictureReviewed = $pictureRepository->findByPictureMostReview();

    return $this->json($pictureReviewed, 200, [],["groups"=>["picture"]]);
    }    

     /**
     * Affiche les 30 images par IA / Display the 30 last pictures by AI
     * @Route("/pictures/filtre/ia", name="app_api_pictures_browseMostByAi", methods={"GET"})
     */
    public function browseMostByAi(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesIa = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesIa, 200, [],["groups"=>["picture"]]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       CONSULTER UN COMPTE UTILISATEUR/ SEE USER ACCOUNT                                                                          
     **********************************************************************************************************************************************************************************************************/

     /**
     * Affiche toute les images d'un utilisateur / Display all the pictures from an user
     * @Route("/pictures/user/{id}/list", name="app_api_pictures_browsePicturesUser", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function browsePicturesUser(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesUser = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesUser, 200, [],["groups"=>["picture"]]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       ACTION UTILISATEUR / CALL TO ACTION USER                                                                        
     **********************************************************************************************************************************************************************************************************/

      /**
     * Permet à un utilisateur de mettre un commentaire à une image
     * 
     * @Route("/pictures/{id}/review", name="app_api_pictures_addReview", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function addReview(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

      /**
     * Permet à un utilisateur de mettre un like à une image
     * 
     * @Route("/pictures/{id}/add/like", name="app_api_pictures_addLike", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function addLike(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

     /**
     * Permet à un utilisateur de mettre un like à une image
     * 
     * @Route("/pictures/{id}/dislike", name="app_api_pictures_dislike", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function dislike(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }


    /**********************************************************************************************************************************************************************************************************
                                                                                ACTION COMPTE UTILISATEUR/ ACTION FROM USER ACCOUNT                                                                        
     **********************************************************************************************************************************************************************************************************/

 /**
     * Permet à un utilisateur d'ajouter une image
     * 
     * @Route("/pictures/add", name="app_api_pictures_addPicture", methods={"POST"})
     */
    public function addPicture(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * Permet à un utilisateur de supprimer une image
     * 
     * @Route("/pictures/{id}/delete", name="app_api_pictures_deletePicture", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deletePicture(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       BARRE DE RECHERCHE/ SEARCH BAR                                                                   
     **********************************************************************************************************************************************************************************************************/

    /**
    * Permet de faire une recherche par prompt / find picture by prompt
    * 
    * @Route("/pictures/search/prompt", name="app_pictures_searchByPrompt", methods={"POST"})
    */
    public function searchByPrompt(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
    * Permet de faire une recherche par tag / find picture by tag
    * 
    * @Route("/pictures/search/tag", name="app_pictures_searchByTag", methods={"POST"})
    */
    public function searchByTag(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

     /**
    * Permet de faire une recherche par nom d'utilisateur / find pictures by user name
    * 
    * @Route("/pictures/search/user", name="app_pictures_searchByUser", methods={"POST"})
    */
    public function searchByUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
