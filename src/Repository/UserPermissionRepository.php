<?php

namespace App\Repository;

use App\Entity\UserPermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserPermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPermission[]    findAll()
 * @method UserPermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPermissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserPermission::class);
    }

    public function findByUserId(int $userId)
    {
        $conn = $this->getEntityManager()->getConnection();

        $query = 'SELECT p.*
                  FROM wbsm_user_permission up
                  JOIN wbsm_user u ON (u.id = up.user_id)
                  JOIN wbsm_permission p ON (p.id = up.permission_id)
                  WHERE up.user_id = :userId
                  ORDER BY p.identifier';
        $handle = $conn->prepare($query);
        $handle->bindValue(':userId', $userId);
        $handle->execute();
        return $handle->fetchAll();
    }

//    /**
//     * @return UserPermission[] Returns an array of UserPermission objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPermission
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
