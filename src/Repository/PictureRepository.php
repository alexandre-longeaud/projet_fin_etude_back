<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        return $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

   /**
    * retourne les 30 images les plus likées 
    */

    public function findPictureByLikes()
    {
        // recupere la connexion à la bdd
        $conn = $this->getEntityManager()->getConnection();

        // la requete qui correspond à cherche les films, trie les aléatoirement et garde en qu'un.
        // movie m correspond à la table movie et pendant la requête on peut y faire référence avec la lettre m
        $sql = '
        SELECT picture.*, COUNT(like.id) AS nombre_like FROM picture
        INNER JOIN `like` ON picture.id = like.picture_id
        GROUP BY picture.id
        ORDER BY nombre_like DESC
        LIMIT 30
        
        ';

        // on execute la requête
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();


        // returns an array
        return $resultSet->fetchAssociative();
    }
   /**
    * retourne les 30 images les plus vues 
    */

    public function findPictureByViews()
    {
        // recupere la connexion à la bdd
        $conn = $this->getEntityManager()->getConnection();

        // la requete qui correspond à cherche les films, trie les aléatoirement et garde en qu'un.
        // movie m correspond à la table movie et pendant la requête on peut y faire référence avec la lettre m
        $sql = '
            SELECT * FROM `picture`
            ORDER BY RAND() 
            LIMIT 30
            ';

        // on execute la requête
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();


        // returns an array
        return $resultSet->fetchAssociative();
    }

    public function findByPictureMostReview()
    {
        // recupere la connexion à la bdd
        $conn = $this->getEntityManager()->getConnection();

        // la requete qui correspond à cherche les films, trie les aléatoirement et garde en qu'un.
        // movie m correspond à la table movie et pendant la requête on peut y faire référence avec la lettre m
        $sql = '
        SELECT picture.*, user.*,COUNT(review.id) AS nombre_commentaires 
        FROM picture
        INNER JOIN review ON picture.id = review.picture_id
        INNER JOIN user ON picture.user_id = user.id
        GROUP BY picture.id
        ORDER BY nombre_commentaires DESC
        LIMIT 30;
            ';

        // on execute la requête
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();



        // returns an array
        return $resultSet->fetchAssociative();
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
