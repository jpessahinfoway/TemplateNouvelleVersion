<?php

namespace App\Repository\Main;

use App\Entity\Main\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * Return an array which contain role name
     * (e.g : array X [
     *                      0 => '__NAME__'
     *                      1 => ...
     *                ]
     * )
     *
     * @return array
     */
    public function getNamesOfAllRoles(): array
    {
        $roles = [];

        foreach ($this->createQueryBuilder('r')->select('r.name')->getQuery()->getResult() as $k => $v)
        {
            foreach ($v as $name)
            {
                $roles[] = $name;
            }
        }

        return $roles;
    }


    public function getAllRolesGreaterUserRole(Role $userRole)
    {
        return $this->createQueryBuilder('r')
                    ->andWhere('r.id >= :id')
                    ->setParameter('id', $userRole->getId())
                    ->getQuery()
                    ->getResult();
    }



    // /**
    //  * @return Role[] Returns an array of Role objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Role
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
