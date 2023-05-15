<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    /**
     * Affiche la liste des utilisateurs
     * 
     * @Route("/", name="app_back-office_users_browseUser", methods={"GET"})
     */

    public function browseUser(): Response
    {
        return $this->render('user/list.html.twig');
    }

 

}
