<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/api", name="app_api_home")
 */
class UserController extends AbstractController
{
   

     /**
     *Affiche le compte d'un utilisateur / Display user account
     *  
     * @Route("/users/{id}/account", name="app_users_browseAccountUser",requirements={"id"="\d+"}, methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function browseAccountUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * Permet de modifier le profil d'un utilisateur
     * 
     * @Route("/users/{id}/account/edit", name="app_users_editProfilUser",requirements={"id"="\d+"}, methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function editProfilUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
    * Permet de modifier la biographie d'un utilisateur
    *
    * @Route("/users/{id}/account/edit", name="app_users_editAccountUser",requirements={"id"="\d+"}, methods={"PUT"})
    * @IsGranted("ROLE_USER")
    */
    public function editAccountUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

}
