<?php

namespace App\Controller\Api;

use App\Repository\PictureRepository;
use App\Repository\LikeRepository;
use App\Entity\Picture;
use App\Entity\Like;
use App\Entity\Review;
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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use App\Service\FileUploader;
use Lcobucci\JWT\Validation\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function browseByCreatedAt(PictureRepository $pictureRepository,LikeRepository $likeRepository,SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $listPictures=[];
        //On récupères les 30 dernière images les plus récente 
        $picturesAtHome = $pictureRepository->findPictureOrderByDate();
        //Pour chaque image, on boucle sur chaque image 
        foreach ($picturesAtHome as $picture) {

            /**
             * @var Serializer $serializer
             */
            $normalizePicture= $serializer->normalize($picture,'array',["groups"=>["picture"]]);
            $normalizePicture=$normalizePicture['0'];
         
            //On détermine si une image liké en metant une variable par défault à true
            $isLiked=true;
            //Si un utilisateur n'est pas connecté alors on ne fournit pas l'info est on met $isLiked à false pour tout!!
            if($user === null){
                $isLiked=false;
            }else{
            //Si utilisateur connecté, on vas déterminé pour cette image ci, on détermine si elle est liké ou non par cette utilisateur
            // On instancie la méthode finOneBy du repo et lui passe une tableau associatiif pour qu'il compare les user vs picture (relation many to many sans attribut) soit les deux clés étrangère prsente dans la table like.
                $like=$likeRepository->findOneBy([
                    'user'=>$user,
                    'picture'=>$picture
                ]);
                //si c'est = à null il n'y pas à de relation entre user et picture et donc pas de like.
                if ($like === null){
                    $isLiked=false;
                }
            }
                $normalizePicture['nombre_like'] = $picture['nombre_like'];
                $normalizePicture['nombre_review'] = $picture['nombre_review'];
                $normalizePicture['isLiked'] =$isLiked;
           
            $listPictures[]=$normalizePicture;

        }
        
        return $this->json($listPictures);
    }

    /**
     * Affiche l'image de la semaine/ Display picture of the week
     * 
     * @Route("/pictures/week", name="app_api_pictures_browsePictureWeek", methods={"GET"})
     */
    public function browsePictureWeek(Request $request, PictureRepository $pictureRepository, LikeRepository $likeRepository): Response
    {
        $endDate = new DateTime();
        $startDate = (clone $endDate)->modify('-1 days');        
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

        $user = $this->getUser();
        $isLiked = false;

        if ($user) {
            // Vérifiez si l'utilisateur a liké l'image de la semaine
            $like = $likeRepository->findOneBy([
                'user' => $user,
                'picture' => $imageOfTheWeek
            ]);
        
            if ($like) {
                $isLiked = true;
            }
        }
        
        $picture['isLiked'] = $isLiked;       
    
        return $this->json($picture, 200, [], ['groups' => ['picture']]);
    }
    
     /**
     * Affiche l'image selectionnée / Display the selected picture
     * 
     * @Route("/pictures/{id}", name="app_api_pictures_read", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function read($id, PictureRepository $pictureRepository, LikeRepository $likeRepository): JsonResponse
    {
        $user = $this->getUser();        
        $picture = $pictureRepository->findPicture($id);

        if ($picture === null){return $this->json("image inexistant",Response::HTTP_NOT_FOUND);}
        $isLiked = false;

        if ($user) {
            // Vérifiez si l'utilisateur a liké cette image
            $like = $likeRepository->findOneBy([
                'user' => $user,
                'picture' => $picture
            ]);
        
            if ($like) {
                $isLiked = true;
            }
        }
        
        $picture['isLiked'] = $isLiked;        
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
    public function browseMostLiked(PictureRepository $pictureRepository,LikeRepository $likeRepository,SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $listPictures=[];
        $picturesLiked = $pictureRepository->findPictureByLikes();

         //Pour chaque image, on boucle sur chaque image 
    foreach ($picturesLiked as $picture) {

    
            /**
             * @var Serializer $serializer
             */
            $normalizePicture= $serializer->normalize($picture,'array',["groups"=>["picture"]]);
            $normalizePicture=$normalizePicture['0'];
    //On détermine si une image liké en metant une variable par défault à true
    $isLiked=true;
    //Si un utilisateur n'est pas connecté alors on ne fournit pas l'indo est on met $isLiked à false pour tout!!
    if($user === null) {
        $isLiked=false;
    } else {
    //Si utilisateur connecté, on vas déterminé pour cette image ci, on détermine si elle est liké ou non par cette utilisateur
    // On instancie la méthode finOneBy du repo et lui passe une tableau associatiif pour qu'il compare les user vs picture (relation many to many sans attribut) soit les deux clés étrangère prsente dans la table like.
    $like=$likeRepository->findOneBy([
        'user'=>$user,
        'picture'=>$picture
    ]);
    //si c'est = à null il n'y pas à de relation entre user et picture et donc pas de like.
    if ($like === null) {
        $isLiked=false;
    }
}
        $normalizePicture['nombre_like'] = $picture['nombre_like'];
        $normalizePicture['nombre_review'] = $picture['nombre_review'];
        $normalizePicture['isLiked'] =$isLiked;
           
        $listPictures[]=$normalizePicture;

  
}

        return $this->json($listPictures);
    }

      /**
     * Affiche les 30 images les plus regardées/cliquées / Display the 30 most clicked pictures
     * 
     * @Route("/pictures/filtre/clicked", name="app_api_picture_browseMostClicked", methods={"GET"})
     */
    public function browseMostClicked(PictureRepository $pictureRepository,LikeRepository $likeRepository,SerializerInterface $serializer): JsonResponse
    {

        $user = $this->getUser();
        $listPictures=[];

        $picturesClicked = $pictureRepository->findPicturerByNbClic();

               //Pour chaque image, on boucle sur chaque image 
    foreach ($picturesClicked as $picture) {

          /**
             * @var Serializer $serializer
             */
            $normalizePicture= $serializer->normalize($picture,'array',["groups"=>["picture"]]);
            $normalizePicture=$normalizePicture['0'];
        //On détermine si une image liké en metant une variable par défault à true
        $isLiked=true;
        //Si un utilisateur n'est pas connecté alors on ne fournit pas l'indo est on met $isLiked à false pour tout!!
        if($user === null) {
            $isLiked=false;
        } else {
    //Si utilisateur connecté, on vas déterminé pour cette image ci, on détermine si elle est liké ou non par cette utilisateur
    // On instancie la méthode finOneBy du repo et lui passe une tableau associatiif pour qu'il compare les user vs picture (relation many to many sans attribut) soit les deux clés étrangère prsente dans la table like.
    $like=$likeRepository->findOneBy([
        'user'=>$user,
        'picture'=>$picture
    ]);
    //si c'est = à null il n'y pas à de relation entre user et picture et donc pas de like.
    if ($like === null) {
        $isLiked=false;
    }
}

        $normalizePicture['nombre_like'] = $picture['nombre_like'];
        $normalizePicture['nombre_review'] = $picture['nombre_review'];
        $normalizePicture['isLiked'] =$isLiked;
           
        $listPictures[]=$normalizePicture;

    
        
    }

        return $this->json($listPictures, 200);
    }

     /**
     * Affiche les 30 images les plus commentées / Display the 30 most commented pictures
     * 
     * @Route("/pictures/filtre/reviewed", name="app_api_pictures_browseMostReviewed", methods={"GET"})
     */
    public function browseMostReviewed(PictureRepository $pictureRepository,LikeRepository $likeRepository,SerializerInterface $serializer): JsonResponse
    {

        $user = $this->getUser();
        $listPictures=[];

    
    $pictureReviewed = $pictureRepository->findByPictureMostReview();

    //Pour chaque image, on boucle sur chaque image 
    foreach ( $pictureReviewed as $picture) {

          /**
             * @var Serializer $serializer
             */
            $normalizePicture= $serializer->normalize($picture,'array',["groups"=>["picture"]]);
            $normalizePicture=$normalizePicture['0'];
     //On détermine si une image liké en metant une variable par défault à true
     $isLiked=true;
     //Si un utilisateur n'est pas connecté alors on ne fournit pas l'indo est on met $isLiked à false pour tout!!
     if($user === null) {
         $isLiked=false;
     } else {
    //Si utilisateur connecté, on vas déterminé pour cette image ci, on détermine si elle est liké ou non par cette utilisateur
    // On instancie la méthode finOneBy du repo et lui passe une tableau associatiif pour qu'il compare les user vs picture (relation many to many sans attribut) soit les deux clés étrangère prsente dans la table like.
    $like=$likeRepository->findOneBy([
        'user'=>$user,
        'picture'=>$picture
    ]);
    //si c'est = à null il n'y pas à de relation entre user et picture et donc pas de like.
    if ($like === null) {
        $isLiked=false;
    }
}
 
        $normalizePicture['nombre_like'] = $picture['nombre_like'];
        $normalizePicture['nombre_review'] = $picture['nombre_review'];

        $normalizePicture['isLiked'] =$isLiked;
           
        $listPictures[]=$normalizePicture;

 
     
 }

    return $this->json($listPictures, 200);
    }    


    /**********************************************************************************************************************************************************************************************************
                                                                                       ACTION UTILISATEUR / CALL TO ACTION USER                                                                        
     **********************************************************************************************************************************************************************************************************/

      /**
     * Permet à un utilisateur de mettre un commentaire à une image
     * 
     * @Route("/pictures/{id}/review", name="app_api_pictures_addReview", requirements={"id"="\d+"}, methods={"POST"})
     * @IsGranted("ROLE_USER")
     */
    public function addReview(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager,Picture $picture): JsonResponse
    {

        $user = $this->getUser();
        

        //Vérifier si l'utilisateur est connecté
            if (!$user) {
          return new JsonResponse(['message' => 'Il faut être connecté pour ajouter un commentaire'], 401);
        }

    //Je récupère les données avec l'object Request et sa méthode getContent()
    $jsonRecu = $request->getContent();
    //$jsonData=json_decode($jsonRecu);



    try {
        //Nous devons désérialiszer les données, pour cela on utilise l'object SerializerInterface et sa méthode déserialize().
        //On lui passe en argument les données, on précise qu'on souhaite avoir les données en entité Picture, et pour terminer on précise le format de donnée recupérer (json)
        $review = $serializer->deserialize($jsonRecu, Review::class, 'json');


        $review->setUser($user);
        $review->setCreatedAt(new \DateTimeImmutable());

        $picture->addReview($review);
        //L'entityManagerInterface permet de récuperer et d'envoyer les données en bdd

        //On vérifie si toutes les données correspondent bien aux validations souhaiter
        //$error=$validator->validate($picture);

        //Si il y a des erreurs de validation, on renvoie un status 400 pour prévenir qu'il y a un problème de validation
        //if(count($error)>0){
        //    return $this->json($error,400);
        //}

        $manager->persist($review);
        $manager->flush();

        return $this->json($review, 201, [], ['groups'=> ["add-review"]]);
    }
    //si l'encodage n'est pas bon, je retourne en format json un tableau(status et message d'erreur) et un status 400 (utise pour éviter que le front tombe sur un message d'erreur html illisible)
    catch(NotEncodableValueException $e) {
        return $this->json([
            'status'=>400,
            'message'=> $e->getMessage()
        ], 400);
    }
}

    /**
     * Permet à un utilisateur de mettre un like ou dislike
     * 
     * @Route("/pictures/{id}/like", name="app_api_pictures_addLike", requirements={"id"="\d+"}, methods={"POST"})
     * @IsGranted("ROLE_USER")
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
     * @IsGranted("ROLE_USER")
     */
    public function addPicture(Request $request,SerializerInterface $serializer,EntityManagerInterface $manager, FileUploader $fileUploader,ValidatorInterface $validator)
    {

        $user = $this->getUser();


        //Vérifier si l'utilisateur est connecté
        if (!$user) {
            return new JsonResponse(['message' => 'Il faut ce connecter pour ajouter une image'], 401);
          }

        $fichier=($request->files->get("file"));
        //Je récupère les données avec l'object Request et sa méthode getContent()
        $data=$request->request->get("data");
        
       
       //$jsonData=json_decode($jsonRecu);

       try {
        //Nous devons désérialiszer les données, pour cela on utilise l'object SerializerInterface et sa méthode déserialize(). 
    //On lui passe en argument les données, on précise qu'on souhaite avoir les données en entité Picture, et pour terminer on précise le format de donnée recupérer (json)
    $picture = $serializer->deserialize($data, Picture::class,'json');
    //dd($picture);
       
       //$picture->setUser($user);
       $picture->setCreatedAt(new \DateTimeImmutable());
       $picture->setUser($user);

    //L'entityManagerInterface permet de récuperer et d'envoyer les données en bdd

    //On vérifie si toutes les données correspondent bien aux validations souhaiter
    $error=$validator->validate($picture);

    //Si il y a des erreurs de validation, on renvoie un status 400 pour prévenir qu'il y a un problème de validation
    if(count($error)>0){
    return $this->json($error,400);
    
    }

    $fileName= $fileUploader->upload($fichier);
    //dd($fileName);
    $picture->setFileName($fileName);
    
       $manager->persist($picture);
       $manager->flush();
       
       return $this->json($picture,201,[],['groups'=> ["add-picture"]]);
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
     * @IsGranted("ROLE_USER")
     */
    public function deletePicture(EntityManagerInterface $entityManager, Picture $picture): Response
    {

        $user = $this->getUser();
        

        //Vérifier si l'utilisateur est connecté
            if (!$user) {
          return new JsonResponse(['message' => 'Il faut être connecté pour supprimer une image'], 401);
        }

        // Supprimer les commentaires associés à l'image
        foreach ($picture->getReviews() as $review) {
            $entityManager->remove($review);
        }

        // Supprimer les likes associés à l'image
        foreach ($picture->getLikes() as $like) {
            $entityManager->remove($like);
        }

        // Supprimer l'image
        $entityManager->remove($picture);
        $entityManager->flush();


        return new Response('L\'image et les commentaires associés ont été supprimés avec succès.', Response::HTTP_OK,[],["groups"=>["delete"]]);
    }

    
    

    /**********************************************************************************************************************************************************************************************************
                                                                                       BARRE DE RECHERCHE/ SEARCH BAR                                                                   
     **********************************************************************************************************************************************************************************************************/

    /**
    * Permet de faire une recherche par prompt / find picture by prompt
    * 
    * @Route("/pictures/search/prompt", name="app_pictures_searchByPrompt", methods={"POST"})
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
    public function searchByUser(Request $request,EntityManagerInterface $manager): JsonResponse
    {
        $search = $request->query->get('search');
        $pictures = $manager->getRepository(Picture::class)->findByUser($search);

        return $this->json($pictures, 200, [],["groups"=>["prompt"]]);
    }
}
