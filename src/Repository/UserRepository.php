<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;



/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function findUser($id)
    {
        $qb = $this->createQueryBuilder('user');

        $userData = $qb
            ->select('user.id, user.pseudo, user.email, user.bio, user.avatar, COUNT(DISTINCT p.id) AS total_pictures')
            ->leftJoin('user.pictures', 'p')
            ->leftJoin('user.likes', 'l')
            ->andWhere('user.id = :id')
            ->setParameter('id', $id)
            ->groupBy('user.id, user.pseudo, user.email')
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
        
        if ($userData) {
            $picturesData = $this->createQueryBuilder('user')
                ->select('p.id AS picture_id')
                ->addSelect('p.fileName AS picture_fileName')
                ->addSelect('COUNT(DISTINCT pl.id) AS likesCount')
                ->addSelect('COUNT(DISTINCT r.id) AS reviewsCount')
                ->leftJoin('user.pictures', 'p')
                ->leftJoin('p.likes', 'pl')
                ->leftJoin('p.reviews', 'r')
                ->andWhere('user.id = :id')
                ->setParameter('id', $id)
                ->groupBy('p.id')
                ->getQuery()
                ->getResult(Query::HYDRATE_ARRAY);
        
            $userData['pictures'] = $picturesData;
        
            return $userData;
        }
        
        return null;   
      }
                    

            public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
