<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends AbstractController
{
    /**
     * Affiche toutes les reviews
     * 
     * @Route("/back/review", name="app_back_review")
     */
    public function list(ReviewRepository $reviewRepository): Response
    {
        $reviews =$reviewRepository->findAll();

        return $this->render('back/review/list.html.twig', [
            'reviews' => $reviews
        ]);
    }

    /**
     * Affiche la review selectionner
     * 
     * @Route("/back/review/{id}", name="app_back_show_review", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(ReviewRepository $reviewRepository, int $id): Response
    {
        $review =$reviewRepository->find($id);

        return $this->render('back/review/show.html.twig', [
            'reviews' => $review
        ]);
    }

     /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/back/review/{id}/delete", name="app_back_delete_review",requirements={"id"="\d+"})
     */
    public function delete(EntityManagerInterface $entityManager,Review $review)
    {

        $this->addFlash(
            'warning review',
            "Le commentaire de ".$review->getUser()->getPseudo(). " a été supprimé !"
        );

        $entityManager->remove($review);
 
             $entityManager->flush();
 
             return $this ->redirectToRoute("app_back_review");
    }

     /**
    * Permet de faire une recherche par nom d'utilisateur / find pictures by user name
    * 
    * @Route("/search/user", name="app_back_search")
    */
    public function searchByUser(Request $request,EntityManagerInterface $manager): Response
    {
        $users = $manager->getRepository(User::class)->findAllOrderByTitleSearch($request->get("search"));

        return $this->render('back/review/list.html.twig',[
           "users" => $users
        ]);
    }


}
