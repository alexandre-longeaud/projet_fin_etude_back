<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api", name="app_api_home")
 */
class UserController extends AbstractController
{
   

     /**
     *Affiche le compte d'un utilisateur / Display user account
     *  
     * @Route("/users/{id}/account", name="app_users_browseAccountUser",requirements={"id"="\d+"}, methods={"GET"})
     */
    public function browseAccountUser($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findUser($id);

        if ($user === null){return $this->json("user inexistant",Response::HTTP_NOT_FOUND);}
        return $this->json($user, 200, [], ["groups"=>["picture"]]);
    }

    /**
     * Permet de modifier le profil d'un utilisateur
     * 
     * @Route("/users/{id}/account/edit", name="app_users_editProfilUser",requirements={"id"="\d+"}, methods={"PUT"})
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
    */
    public function editAccountUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

}
