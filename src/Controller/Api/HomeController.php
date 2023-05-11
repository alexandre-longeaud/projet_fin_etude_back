<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/home", name="app_api_home")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list(PictureRepository $pictureRepository): JsonResponse
    {
        $allPictures = $pictureRepository->findAll();
        return $this->json($allPictures, 200, []);
    }

     /**
     * @Route("/list", name="findPictureOrderByDate", methods={"GET"})
     */
    public function picturesHome(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>"picture"]);
    }

     /**
     * @Route("/{id}", name="read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function zoom($id, PictureRepository $pictureRepository): JsonResponse
    {
        $picture = $pictureRepository->find($id);

        if ($picture === null){return $this->json("messages d'erreur",Response::HTTP_NOT_FOUND);}

        return $this->json($picture, 200, [], ["groups"=>"picture"]);
    }
}
