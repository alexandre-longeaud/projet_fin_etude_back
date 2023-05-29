<?php

namespace App\Controller\Back;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Review;
use App\Repository\PictureRepository;
use App\Entity\Picture;
use App\Entity\User;

class PictureController extends AbstractController
{
    /**
     * Affiche toutes les images
     * 
     * @Route("/back/picture", name="app_back_picture")
     */
    public function list(PictureRepository $pictureRepository): Response
    {
        $pictures =$pictureRepository->findAll();

        return $this->render('picture/list.html.twig', [
            'pictures' => $pictures
        ]);
    }

    /**
     * Affiche l'image' selectionner
     * 
     * @Route("/back/picture/{id}", name="app_back_show_picture", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(PictureRepository $pictureRepository, int $id): Response
    {
        $picture =$pictureRepository->find($id);

        return $this->render('picture/show.html.twig', [
            'picture' => $picture
        ]);
    }

     /**
     * Permet de supprimer une image
     * 
     * @Route("/back/picture/{id}/delete", name="app_back_delete_picture",requirements={"id"="\d+"})
     */
    public function delete(EntityManagerInterface $entityManager,Picture $picture)
    {

        $this->addFlash(
            'warning picture',
            "L'image de ".$picture->getUser()->getPseudo(). " a été supprimé !"
        );

        $entityManager->remove($picture);
 
             $entityManager->flush();
 
             return $this ->redirectToRoute("app_back_picture");
    }




}
