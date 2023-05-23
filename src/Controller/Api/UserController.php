<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;



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
     * @Route("/users/{id}/account/profil", name="app_users_editProfilUser",requirements={"id"="\d+"}, methods={"PUT"})
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
    * @Route("/users/{id}/account/bio", name="app_users_editAccountUser",requirements={"id"="\d+"}, methods={"PUT"})
    */
    public function editAccountUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    /**
     * Affichage du formulaire d'inscription
     *
     * @Route("/users/sign-up", name="app_users_signUp", methods={"POST"})
     */
    public function signUp(Request $request, SerializerInterface $serializer,UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $manager){

        $data = $request->getContent();
        $user = $serializer->deserialize($data, User::class, 'json');

        // Vérification si l'utilisateur existe déjà avec la même adresse e-mail
    $existingUser = $manager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
    if ($existingUser) {
        return new JsonResponse(['message' => 'User with the same email already exists'], Response::HTTP_BAD_REQUEST);
    }

        
        // Hachage du mot de passe
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        $user->setCreatedAt(new \DateTimeImmutable());
        
        $manager->persist($user);
        $manager->flush();
        
        return new JsonResponse(['message' => 'Inscription créer avec succès!!'], Response::HTTP_CREATED);
    }


    }


