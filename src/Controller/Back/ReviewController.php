<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReviewRepository ;

class ReviewController extends AbstractController
{
    /**
     * @Route("/back/review", name="app_back_review")
     */
    public function list(ReviewRepository $reviewRepository): Response
    {
        $reviews =$reviewRepository->findAll();

        return $this->render('back/review/list.html.twig', [
            'reviews' => $reviews
        ]);
    }
}
