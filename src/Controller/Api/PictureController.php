<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use App\Repository\LikeRepository;
use App\Entity\Picture;
use App\Entity\Like;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ImageSelectionService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/api")
 */
class PictureController extends AbstractController
{

    private $imageSelectionService;

    public function __construct(ImageSelectionService $imageSelectionService)
    {
        $this->imageSelectionService = $imageSelectionService;
    }
    /**********************************************************************************************************************************************************************************************************
                                                                                            PAGE ACCUEIL/HOMEPAGE
     **********************************************************************************************************************************************************************************************************/
  
     /**
     * Affiche les 30 images les plus récente en page d'accueil / Display the 30 most recents pictures on homepage
     * 
     * @Route("/pictures", name="app_api_pictures_browseByCreatedAt", methods={"GET"})
     * 
     */
    public function browseByCreatedAt(PictureRepository $pictureRepository): JsonResponse
    {
        
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesAtHome, 200, [],["groups"=>["picture"]]);
    }

    /**
     * Affiche l'image de la semaine/ Display picture of the week
     * 
     * @Route("/pictures/week", name="app_api_pictures_browsePictureWeek", methods={"GET"})
     */
    public function browsePictureWeek(Request $request, PictureRepository $pictureRepository): Response
    {
        $endDate = new DateTime();
        $startDate = (clone $endDate)->modify('-7 days');        
        // Utilisez le service de sélection de l'image de la semaine pour obtenir l'image sélectionnée
        $imageOfTheWeek = $this->imageSelectionService->selectImageOfTheWeek($startDate, $endDate);
    
        if (!$imageOfTheWeek) {
            return $this->json(['error' => 'No image of the week selected'], 404);
        }
    
        // Récupérez les informations complètes de l'image sélectionnée depuis le repository
        $picture = $pictureRepository->findPicture($imageOfTheWeek->getId());
    
        if (!$picture) {
            return $this->json(['error' => 'Picture not found'], 404);
        }
    
        return $this->json($picture, 200, [], ['groups' => ['picture']]);
    }
    
     /**
     * Affiche l'image selectionnée / Display the selected picture
     * 
     * @Route("/pictures/{id}", name="app_api_pictures_read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, PictureRepository $pictureRepository): JsonResponse
    {
        $picture = $pictureRepository->findPicture($id);

        if ($picture === null){return $this->json("image inexistant",Response::HTTP_NOT_FOUND);}

        return $this->json($picture, 200, [], ["groups"=>["picture"]]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       FILTRES PAGE ACCUEIL/FILTER HOMEPAGE                                                                          
     **********************************************************************************************************************************************************************************************************/

      /**
     * Affiche les 30 images les plus likées / Display the 30 most liked pictures
     * 
     * @Route("/pictures/filtre/liked", name="app_api_picture_browseMostLiked", methods={"GET"})
     */
    public function browseMostLiked(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesLiked = $pictureRepository->findPictureByLikes();
        return $this->json($picturesLiked, 200, [],["groups"=>["picture"]]);
    }

      /**
     * Affiche les 30 images les plus regardées/cliquées / Display the 30 most clicked pictures
     * 
     * @Route("/pictures/filtre/clicked", name="app_api_picture_browseMostClicked", methods={"GET"})
     */
    public function browseMostClicked(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesClicked = $pictureRepository->findPicturerByNbClic();
        return $this->json($picturesClicked, 200, [],["groups"=>["picture"]]);
    }

     /**
     * Affiche les 30 images les plus commentées / Display the 30 most commented pictures
     * 
     * @Route("/pictures/filtre/reviewed", name="app_api_pictures_browseMostReviewed", methods={"GET"})
     */
    public function browseMostReviewed(PictureRepository $pictureRepository): JsonResponse
    {
    $pictureReviewed = $pictureRepository->findByPictureMostReview();

    return $this->json($pictureReviewed, 200, [],["groups"=>["picture"]]);
    }    

     /**
     * Affiche les 30 images par IA / Display the 30 last pictures by AI
     * @Route("/pictures/filtre/ia", name="app_api_pictures_browseMostByAi", methods={"GET"})
     */
    public function browseMostByAi(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesIa = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesIa, 200, [],["groups"=>["picture"]]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       CONSULTER UN COMPTE UTILISATEUR/ SEE USER ACCOUNT                                                                          
     **********************************************************************************************************************************************************************************************************/

     /**
     * Affiche toute les images d'un utilisateur / Display all the pictures from an user
     * @Route("/pictures/user/{id}/list", name="app_api_pictures_browsePicturesUser", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function browsePicturesUser(PictureRepository $pictureRepository): JsonResponse
    {
        $picturesUser = $pictureRepository->findPictureOrderByDate();
        return $this->json($picturesUser, 200, [],["groups"=>["picture"]]);
    }

    /**********************************************************************************************************************************************************************************************************
                                                                                       ACTION UTILISATEUR / CALL TO ACTION USER                                                                        
     **********************************************************************************************************************************************************************************************************/

      /**
     * Permet à un utilisateur de mettre un commentaire à une image
     * 
     * @Route("/pictures/{id}/review", name="app_api_pictures_addReview", requirements={"id"="\d+"}, methods={"POST"})
     */
    public function addReview(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

      /**
     * Permet à un utilisateur de mettre un like à une image
     * 
     * @Route("/pictures/{id}/like", name="app_api_pictures_addLike", requirements={"id"="\d+"}, methods={"POST"})
     * IsGranted("ROLE_USER")
     */
    public function addLike(Picture $picture, EntityManagerInterface $manager): JsonResponse
    {
        
        $user = $this->getUser();
        

        //Vérifier si l'utilisateur est connecté
            if (!$user) {
          return new JsonResponse(['message' => 'Il faut ce connecter pour liker'], 401);
        }

        // Vérifier si l'utilisateur a déjà aimé cette image
        if ($picture->isLikedByUser($user)) {

        $like = $picture->findLikeByUser($user);

        $manager->remove($like);
        $manager->flush();


            return new JsonResponse(['message' => 'dislike ok !'], 201);

            
        }

        // Créer une nouvelle entité Like
        $like = new Like();
        $like->setUser($user);
        $like->setPicture($picture);

        // Ajouter le like à l'entité Picture
        $picture->addLike($like);

        
        $manager->persist($like);
        $manager->flush();

        return new JsonResponse(['message' => 'Like ajouté avec succès.']);
    }

    

    /**********************************************************************************************************************************************************************************************************
                                                                                ACTION COMPTE UTILISATEUR/ ACTION FROM USER ACCOUNT                                                                        
     **********************************************************************************************************************************************************************************************************/

 /**
     * Permet à un utilisateur d'ajouter une image
     * 
     * @Route("/pictures/add", name="app_api_pictures_addPicture", methods={"POST"})
     */
    public function addPicture(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager)
    {
        //Je récupère les données avec l'object Request et sa méthode getContent()
       $jsonRecu = $request->getContent();
       //$jsonData=json_decode($jsonRecu);

       try {
        //Nous devons désérialiszer les données, pour cela on utilise l'object SerializerInterface et sa méthode déserialize(). 
    //On lui passe en argument les données, on précise qu'on souhaite avoir les données en entité Picture, et pour terminer on précise le format de donnée recupérer (json)
       $picture = $serializer->deserialize($jsonRecu, Picture::class,'json');

       $picture->setCreatedAt(new \DateTimeImmutable());
    //L'entityManagerInterface permet de récuperer et d'envoyer les données en bdd

    //On vérifie si toutes les données correspondent bien aux validations souhaiter
    //$error=$validator->validate($picture);

    //Si il y a des erreurs de validation, on renvoie un status 400 pour prévenir qu'il y a un problème de validation
    //if(count($error)>0){
    //    return $this->json($error,400);
    //}
    
       $manager->persist($picture);
       $manager->flush();
       
       return $this->json($picture,201,[],['groups'=> ["picture"]]);
       }
       //si l'encodage n'est pas bon, je retourne en format json un tableau(status et message d'erreur) et un status 400 (utise pour éviter que le front tombe sur un message d'erreur html illisible)
       catch(NotEncodableValueException $e){
        return $this->json([
            'status'=>400,
            'message'=> $e->getMessage()
        ],400);
       }
      
    }

    /**
     * Permet à un utilisateur de supprimer une image
     * 
     * @Route("/pictures/{id}/delete", name="app_api_pictures_deletePicture", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function deletePicture(int $id,EntityManagerInterface $manager): JsonResponse
    {

        
        $picture = $manager->getRepository(Picture::class)->find($id);

        if (!$picture) {
            return new JsonResponse(['message' => 'Image non trouvé.'], 404);
        }

        $manager->remove($picture);
        $manager->flush();

        return new JsonResponse(['message' => 'Image supprimée avec succès.'],200);
    }
    

    /**********************************************************************************************************************************************************************************************************
                                                                                       BARRE DE RECHERCHE/ SEARCH BAR                                                                   
     **********************************************************************************************************************************************************************************************************/

    /**
    * Permet de faire une recherche par prompt / find picture by prompt
    * 
    * @Route("/pictures/search", name="app_pictures_searchByPrompt", methods={"POST"})
    */
    public function searchByPrompt(Request $request,EntityManagerInterface $manager): Response
    {
        $search = $request->query->get('search');
    
        $pictures = $manager->getRepository(Picture::class)->findByPrompt($search);

        // Autres traitements ou rendus de la réponse...
    
        // Par exemple, retourner les images au format JSON
        return $this->json($pictures, 200, [],["groups"=>["prompt"]]);
    }

    /**
    * Permet de faire une recherche par tag / find picture by tag
    * 
    * @Route("/pictures/search/tag", name="app_pictures_searchByTag", methods={"POST"})
    */
    public function searchByTag(Request $request,EntityManagerInterface $manager): JsonResponse
    {
        $search = $request->query->get('search');
        $pictures = $manager->getRepository(Picture::class)->findByTag($search);

        return $this->json($pictures, 200, [],["groups"=>["prompt"]]);



    }

     /**
    * Permet de faire une recherche par nom d'utilisateur / find pictures by user name
    * 
    * @Route("/pictures/search/user", name="app_pictures_searchByUser", methods={"POST"})
    */
    public function searchByUser(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
