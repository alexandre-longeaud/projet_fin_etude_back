<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Func;
use Doctrine\ORM\Query\Expr\Join;
/**
 * @extends ServiceEntityRepository<Picture>
 *
 * @method Picture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Picture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Picture[]    findAll()
 * @method Picture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function add(Picture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Picture $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * retourne la liste des 30 images les plus récentes
    //     */
    public function findPictureOrderByDate(): array
    {
        return $this->createQueryBuilder('picture')
            ->select('picture, COUNT(l.id) AS nombre_like, COUNT(review.id) AS nombre_review, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar' )
            ->leftJoin('picture.likes', 'l')
            ->leftJoin('picture.reviews', 'review')
            ->leftJoin('picture.user', 'user')
            ->groupBy('picture.id')
            ->orderBy('picture.createdAt', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

   /**
    * retourne les 30 images les plus likées 
    */

    public function findPictureByLikes(): array
    {
        return $this->createQueryBuilder('picture')
            ->select('picture, COUNT(l.id) AS nombre_like, COUNT(review.id) AS nombre_review, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar' )
            ->leftJoin('picture.likes', 'l')
            ->leftJoin('picture.reviews', 'review')
            ->leftJoin('picture.user', 'user')
            ->groupBy('picture.id')
            ->orderBy('nombre_like', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }
   /**
    * retourne les 30 images les plus vues 
    */

    public function findPicturerByNbClic()
    {
        return $this->createQueryBuilder('picture')
            ->select('picture, COUNT(r.id) AS nombre_review, COUNT(l.id) AS nombre_like, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar')
            ->leftJoin('picture.likes', 'l')
            ->leftJoin('picture.reviews', 'r')
            ->leftJoin('picture.user', 'user')
            ->groupBy('picture.id')
            ->orderBy('picture.nbClick', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

   /**
    * retourne les 30 images les plus commentées 
    */

    public function findByPictureMostReview()
    {
        return $this->createQueryBuilder('picture')
            ->select('picture, COUNT(r.id) AS nombre_review, COUNT(l.id) AS nombre_like, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar')
            ->leftJoin('picture.likes', 'l')
            ->leftJoin('picture.reviews', 'r')
            ->leftJoin('picture.user', 'user')
            ->groupBy('picture.id')
            ->orderBy('nombre_review', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();  
         }


   /**
    * retourne l'image selectionnée 
    */

    public function findPicture($id)
    {
        $queryBuilder = $this->createQueryBuilder('picture');
    
        $pictureData = $queryBuilder
            ->select('picture.id, picture.url, picture.prompt, picture.nbClick, COUNT(l.id) AS nombre_like, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar, ia.id AS ia_id, ia.name AS ia_name, ia.link AS ia_link')
            ->leftJoin('picture.likes', 'l')
            ->leftJoin('picture.user', 'user')
            ->leftJoin('picture.ia', 'ia')
            ->andWhere('picture.id = :id')
            ->setParameter('id', $id)
            ->groupBy('picture.id, user.id, user.pseudo, user.avatar, ia.id, ia.name, ia.link')
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
        
        if ($pictureData) {
            $reviewsData = $queryBuilder
                ->resetDQLPart('select')
                ->resetDQLPart('groupBy')
                ->select('review.content AS review_content, reviewer.id AS reviewer_id, reviewer.pseudo AS reviewer_pseudo')
                ->leftJoin('picture.reviews', 'review')
                ->leftJoin('review.user', 'reviewer')
                ->andWhere('picture.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);
            
            $pictureData['nombre_review'] = count($reviewsData);
            $pictureData['reviews'] = $reviewsData;
    
            return $pictureData;
        }
    
        return null;
    }

     //    /**
    //     * Retourne un film par 
    //     */
    public function findAllByPrompt(): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.prompt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByPrompt(string $search): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.prompt LIKE :search')
            ->setParameter('search', '%' . $search . '%');

        return $queryBuilder->getQuery()->getResult();
    }


    /**
     * Recherche les images entre deux dates
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return Picture[]
     */
    public function findPicturesBetweenDates(\DateTime $startDate, \DateTime $endDate): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.createdAt >= :startDate')
            ->andWhere('p.createdAt <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }




//    /**
//     * @return Picture[] Returns an array of Picture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Picture
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
