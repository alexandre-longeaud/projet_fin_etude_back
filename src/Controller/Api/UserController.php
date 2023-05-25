<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function browseAccountUser($id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->findUser($id);

        if ($user === null){return $this->json("user inexistant",Response::HTTP_NOT_FOUND);}
        return $this->json($user, 200, [], ["groups"=>["picture"]]);
    }

    /**
     * Permet de modifier le profil d'un utilisateur
     * 
     * @Route("/users/{id}/account/profil", name="app_users_editProfilUser",requirements={"id"="\d+"}, methods={"PUT"})
     * @IsGranted("ROLE_USER")
     */
    public function editProfilUser(Request $request, User $user, UserPasswordHasherInterface $passwordHasher,EntityManagerInterface $manager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier et mettre à jour le pseudo si présent dans la requête
        if (isset($data['pseudo'])) {
            $user->setPseudo($data['pseudo']);
        }
    
        // Vérifier et mettre à jour l'email si présent dans la requête
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
    
        // Vérifier et mettre à jour le mot de passe si présent dans la requête
        if (isset($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
           
        }

        $user->setUpdatedAt(new \DateTimeImmutable());
    
        // Enregistrer les modifications dans la base de données
        $manager->persist($user);
        $manager->flush();
    
        return $this->json($user, 200, [],["groups"=>["update-account"]]);
    }

    /**
    * Permet de modifier la biographie d'un utilisateur connecté
    *
    * @Route("/users/{id}/account/bio", name="app_users_editAccountUser",requirements={"id"="\d+"}, methods={"PUT"})
    * @IsGranted("ROLE_USER")
    */
    public function editAccountUser(request $request,User $user,EntityManagerInterface $manager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier et mettre à jour l'avatar si présent dans la requête
        if (isset($data['avatar'])) {
            $user->setAvatar($data['avatar']);
        }
    
        // Vérifier et mettre à jour la bio si présente dans la requête
        if (isset($data['bio'])) {
            $user->setBio($data['bio']);
        }

        $user->setUpdatedAt(new \DateTimeImmutable());
    
        // Enregistrer les modifications dans la base de données
        
        $manager->persist($user);
        $manager->flush();
    
        return $this->json($user, 200, [],["groups"=>["update-bio"]]);
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

        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["sign-up"]]);
        
        //return new JsonResponse(['message' => 'Inscription créer avec succès!!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/users/info", name="app_api_users_info", methods={"GET"})
     *
     * @return void
     */
    public function info(): JsonResponse
    {
        $user = $this->getUser();

        

        return $this->json($user, Response::HTTP_OK, [], ["groups" => ["read:User:item"]]);
    }

    }


