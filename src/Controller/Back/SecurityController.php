<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("", name="app_security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        

        return $this->render('security/login.html.twig',[
            "last_username" => $lastUsername,
            "error" => $error
        ]);

        return $this->redirectToRoute('app_back_list');
    }

    /**
     * @Route("/logout", name="app_security_logout")
     */
    public function logout()
    {
       
    }
}
