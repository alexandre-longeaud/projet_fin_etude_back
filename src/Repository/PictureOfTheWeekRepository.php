<?php

namespace App\Repository;

use App\Entity\PictureOfTheWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PictureOfTheWeek>
 *
 * @method PictureOfTheWeek|null find($id, $lockMode = null, $lockVersion = null)
 * @method PictureOfTheWeek|null findOneBy(array $criteria, array $orderBy = null)
 * @method PictureOfTheWeek[]    findAll()
 * @method PictureOfTheWeek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PictureOfTheWeekRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PictureOfTheWeek::class);
    }

    public function add(PictureOfTheWeek $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PictureOfTheWeek $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PictureOfTheWeek[] Returns an array of PictureOfTheWeek objects
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

//    public function findOneBySomeField($value): ?PictureOfTheWeek
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
