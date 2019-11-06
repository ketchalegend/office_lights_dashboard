<?php

namespace App\Repository;

use App\Entity\RolePermission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Role\Role;

/**
 * @method RolePermission|null find($id, $lockMode = null, $lockVersion = null)
 * @method RolePermission|null findOneBy(array $criteria, array $orderBy = null)
 * @method RolePermission[]    findAll()
 * @method RolePermission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RolePermissionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RolePermission::class);
    }

    public function findByRoles(array $roles)
    {
        $rolesArray = [];
        foreach ($roles as $role) {
            /** @var Role $role */
            $rolesArray[] = $role->getRole();
        }
        $conn = $this->getEntityManager()->getConnection();

        $query = 'SELECT p.*
                  FROM wbsm_role_permission rp
                  JOIN wbsm_role r ON (r.id = rp.role_id)
                  JOIN wbsm_permission p ON (p.id = rp.permission_id)
                  WHERE r.name IN(?)
                  GROUP BY p.identifier
                  ORDER BY p.identifier';
        $handle = $conn->executeQuery($query, $rolesArray);
        return $handle->fetchAll();
    }
}
