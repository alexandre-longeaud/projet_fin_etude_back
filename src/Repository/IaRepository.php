<?php

namespace App\Repository;

use App\Entity\Ia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ia>
 *
 * @method Ia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ia[]    findAll()
 * @method Ia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ia::class);
    }

    public function add(Ia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // TODO display all the AI with the url

    public function findALLAI()
    {
        // recupere la connexion à la bdd
        $conn = $this->getEntityManager()->getConnection();

        // la requete qui correspond à cherche les films, trie les aléatoirement et garde en qu'un.
        // movie m correspond à la table movie et pendant la requête on peut y faire référence avec la lettre m
        $sql = '
        SELECT * FROM `ia`
        ';

        // on execute la requête
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();


        // returns an array
        return $resultSet->fetchAssociative();
    }

//    /**
//     * @return Ia[] Returns an array of Ia objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ia
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
