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
        $queryBuilder = $this->createQueryBuilder('picture');

        $queryBuilder
            ->select('picture, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar')
            ->leftJoin('picture.user', 'user')
            ->orderBy('picture.createdAt', 'DESC')
            ->setMaxResults(30);
    
        $subQueryLikes = $this->createQueryBuilder('subPictureLikes')
            ->select('COUNT(l.id)')
            ->leftJoin('subPictureLikes.likes', 'l')
            ->where('subPictureLikes = picture')
            ->getDQL();
    
        $subQueryReviews = $this->createQueryBuilder('subPictureReviews')
            ->select('COUNT(r.id)')
            ->leftJoin('subPictureReviews.reviews', 'r')
            ->where('subPictureReviews = picture')
            ->getDQL();
    
        $queryBuilder
            ->addSelect(sprintf('(%s) AS nombre_like', $subQueryLikes))
            ->addSelect(sprintf('(%s) AS nombre_review', $subQueryReviews));
            return $queryBuilder->getQuery()->getResult();
            }

   /**
    * retourne les 30 images les plus likées 
    */

    public function findPictureByLikes(): array
    {
        $subqueryLikes = $this->createQueryBuilder('p1')
        ->select('COUNT(l1.id)')
        ->leftJoin('p1.likes', 'l1')
        ->where('p1.id = picture.id')
        ->getDQL();

    $subqueryReviews = $this->createQueryBuilder('p2')
        ->select('COUNT(r.id)')
        ->leftJoin('p2.reviews', 'r')
        ->where('p2.id = picture.id')
        ->getDQL();

    return $this->createQueryBuilder('picture')
        ->select('picture, (' . $subqueryLikes . ') AS nombre_like, (' . $subqueryReviews . ') AS nombre_review, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar' )
        ->leftJoin('picture.user', 'user')
        ->orderBy('nombre_like', 'DESC')
        ->setMaxResults(30)
        ->getQuery()
        ->getResult();    }
   /**
    * retourne les 30 images les plus vues 
    */

    public function findPicturerByNbClic()
    {
        return $this->createQueryBuilder('picture')
        ->select('picture, COUNT(DISTINCT r.id) AS nombre_review, COUNT(DISTINCT l.id) AS nombre_like, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar')
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
        $subqueryLikes = $this->createQueryBuilder('p1')
        ->select('COUNT(l.id)')
        ->leftJoin('p1.likes', 'l')
        ->where('p1 = picture')
        ->getDQL();

    $subqueryReviews = $this->createQueryBuilder('p2')
        ->select('COUNT(r.id)')
        ->leftJoin('p2.reviews', 'r')
        ->where('p2 = picture')
        ->getDQL();

    return $this->createQueryBuilder('picture')
        ->select('picture, (' . $subqueryLikes . ') AS nombre_like, (' . $subqueryReviews . ') AS nombre_review, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar')
        ->leftJoin('picture.user', 'user')
        ->orderBy('nombre_review', 'DESC')
        ->setMaxResults(30)
        ->getQuery()
        ->getResult();         }


   /**
    * retourne l'image selectionnée 
    */

    public function findPicture($id)
    {
        $queryBuilder = $this->createQueryBuilder('picture');

        $pictureData = $queryBuilder
            ->select('picture.id, picture.fileName, picture.prompt, picture.nbClick, COUNT(l.id) AS nombre_like, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar, ia.id AS ia_id, ia.name AS ia_name, ia.link AS ia_link, tags.id AS tag_id, tags.name AS tag_name')
            ->leftJoin('picture.likes', 'l')
            ->leftJoin('picture.user', 'user')
            ->leftJoin('picture.ia', 'ia')
            ->leftJoin('picture.tags', 'tags')
            ->andWhere('picture.id = :id')
            ->setParameter('id', $id)
            ->groupBy('picture.id, user.id, user.pseudo, user.avatar, ia.id, ia.name, ia.link,tags.id, tags.name')
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    
        if ($pictureData) {
            $reviewsData = $this->createQueryBuilder('picture')
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
        $queryBuilder = $this->createQueryBuilder('picture')
        ->select('picture, COUNT(l.id) AS nombre_like, COUNT(review.id) AS nombre_review, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar,picture.fileName' )
        ->leftJoin('picture.likes', 'l')
        ->leftJoin('picture.reviews', 'review')
        ->leftJoin('picture.user', 'user')
        ->groupBy('picture.id')
        ->where('picture.prompt LIKE :search')
        ->setParameter('search', '%' . $search . '%');

        return $queryBuilder->getQuery()->getResult();
    }


    public function findByTag(string $search): array
    {
        $queryBuilder = $this->createQueryBuilder('picture')
        ->select('picture, picture.id AS picture_id, COUNT(l.id) AS nombre_like, COUNT(review.id) AS nombre_review, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar,picture.fileName, tag.id AS tag_id, tag.name AS tag_name')        
        ->leftJoin('picture.likes', 'l')
        ->leftJoin('picture.reviews', 'review')
        ->leftJoin('picture.user', 'user')
        ->leftJoin('picture.tags', 'tag')
        ->groupBy('picture.id')
        ->where('tag.name LIKE :search')
        ->setParameter('search', '%' . $search . '%');

        return $queryBuilder->getQuery()->getResult();
    }


    public function findByUser(string $search): array
    {
        $queryBuilder = $this->createQueryBuilder('picture')

        ->select('picture, picture.id AS picture_id, COUNT(l.id) AS nombre_like, user.id AS user_id, user.pseudo AS user_pseudo, user.avatar AS user_avatar, picture.fileName')        
        ->leftJoin('picture.likes', 'l')
        ->leftJoin('picture.user', 'user')
        ->leftJoin('picture.tags', 'tag')
        ->groupBy('picture.id')
        ->where('user.pseudo LIKE :search')
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
