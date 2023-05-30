<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\UserType;
use DateTimeImmutable;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *@Route("/back")
 */
class UserController extends AbstractController
{

    /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/", name="app_back_list", methods={"GET"})
     * 
     */

     public function list(UserRepository $userRepository)
     {
        $users=$userRepository->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
     }

      /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/user/{id}", name="app_back_show", methods={"GET"}, requirements={"id"="\d+"})
     * 
     */

    public function show(UserRepository $userRepository,int $id):Response
    {
       $user=$userRepository->find($id);

       return $this->render('user/show.html.twig', [
           'user' => $user
       ]);
    }

      /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/user/add", name="app_back_add", methods={"GET","POST"})
     * 
     */

    public function add(Request $request,UserRepository $userRepository, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher):Response
    {

    $user = new User();

    $form = $this->createForm(UserType::class, $user);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()) {

        // Je pense à hasher le mot de passe
            // Je récupère le mot de passe en clair
            $plainPassword = $user->getPassword();
            // Je le hash
            $passwordHash = $passwordHasher->hashPassword($user,$plainPassword);
            // Je set le mot de passe
            $user->setPassword($passwordHash);

            $userRepository->add($user, true);

        $entityManager->persist($user);

        $entityManager->flush();

        $this->addFlash(
            'success',
            $user->getPseudo() ." a bien été crée !"
        );

        return $this->redirectToRoute('app_back_list');

    }
        return $this->renderForm('user/new.html.twig', [
            'form' => $form,
        ]);

          
        
    }

     /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/user/edit/{id}", name="app_back_edit",requirements={"id"="\d+"})
     * 
     */

    public function edit(Request $request,EntityManagerInterface $entityManager,User $user,UserRepository $userRepository,UserPasswordHasherInterface $passwordHasher):Response
    {
        

        $form = $this->createForm(UserType::class, $user);
    
        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) {

             // Je pense à hasher le mot de passe
            // Je récupère le mot de passe en clair
            $plainPassword = $user->getPassword();
            // Je le hash
            $passwordHash = $passwordHasher->hashPassword($user,$plainPassword);
            // Je set le mot de passe
            $user->setPassword($passwordHash);
            $user->setUpdatedAt(new DateTimeImmutable());

    
            $userRepository->add($user, true);
    
            $entityManager->persist($user);
    
            $entityManager->flush();
    
            $this->addFlash(
                'info'," Vos données ont été modifiées !"
            );
    
            return $this->redirectToRoute('app_back_show',["id"=>$user->getId()]);
    
        }
            return $this->renderForm('user/edit.html.twig', [
                'form' => $form,
            ]);
       
    }

      /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/users/{id}", name="app_back_delete",requirements={"id"="\d+"})
     * 
     */

    public function delete(EntityManagerInterface $entityManager,User $user)
    {

        $this->addFlash(
            'warning',
            $user->getPseudo() ." a été supprimer !"
        );

        $entityManager->remove($user);
 
             $entityManager->flush();
 
             return $this ->redirectToRoute("app_back_list");
    }

     /**
     *Envoyer un mail
     * 
     * @Route("/users/{id}", name="app_back_send",requirements={"id"="\d+"})
     * 
     */

    public function send(EntityManagerInterface $entityManager,User $user)
    {

        
    }

     /**
    * Permet de faire une recherche par nom d'utilisateur / find pictures by user name
    * 
    * @Route("/search/user", name="app_back_search")
    */
    public function searchByUser(Request $request,EntityManagerInterface $manager): Response
    {
        $users = $manager->getRepository(User::class)->findAllOrderByTitleSearch($request->get("search"));

        return $this->render('user/list.html.twig',[
           "users" => $users
        ]);
    }

 
}
