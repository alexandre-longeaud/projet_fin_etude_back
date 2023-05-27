<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 *@Route("/back")
 */
class UserController extends AbstractController
{

    /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/user/list", name="app_back_list", methods={"GET"})
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

    public function add(Request $request, EntityManagerInterface $entityManager):Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class,$user);
        
    
      
    
        return $this->renderForm('user/form.html.twig', [
            'form' => $form
        ]);
       }

     /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/user/{id}/", name="app_back_edit", methods={"PUT"})
     * 
     */

    public function edit()
    {
       

       return $this->render('user/show.html.twig');
    }

      /**
     * Affiche la page d'accueil du back office avec la liste des administrateurs
     * 
     * @Route("/users/{id}/delete", name="app_back_delete", methods={"DELETE"})
     * 
     */

    public function delete()
    {
       

       return $this->render('user/show.html.twig');
    }





  
 
}
