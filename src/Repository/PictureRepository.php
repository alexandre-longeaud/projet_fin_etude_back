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

    public function findPictureByLikes(): array
    {
        return $this->createQueryBuilder('picture')
            ->select('picture, COUNT(l.id) AS nombre_like')
            ->leftJoin('picture.likes', 'l')
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
            ->orderBy('picture.nbClick', 'DESC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function findByPictureMostReview()
    {
        return $this->createQueryBuilder('picture')
        ->select('picture, COUNT(r.id) AS nombre_review')
        ->leftJoin('picture.reviews', 'r')
        ->groupBy('picture.id')
        ->orderBy('nombre_review', 'DESC')
        ->setMaxResults(30)
        ->getQuery()
        ->getResult();   }
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
