<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * Permet à un utilisateur d'ajouter une image
     * 
     * @Route("/users/add/picture", name="app_api_user_addPicture", methods={"POST"})
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
     * @Route("/users/delete/{id}/picture", name="app_api_users_deletePicture", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deletePicture(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * Permet à un utilisateur de modifier ses données personnel de son compte
     * 
     * @Route("/users/patct/{id}/account", name="app_api_users_patchUserAccount", requirements={"id"="\d+"}, methods={"PATCH"})
     */
    public function patchUserAccount(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }


    /**
     * Permet à un utilisateur de mettre un commentaire à une image
     * 
     * @Route("/users/picture/{id}/review", name="app_api_users_AddReview", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function AddReview(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
    
}
