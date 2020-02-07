<?php

namespace App\Repository\Main;

use App\Entity\Main\Action;
use App\Entity\Main\Stage;
use App\Entity\Main\Subject;
use App\Entity\Main\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function checkIfUserExist(array $criteria)
    {
        return $this->findOneBy($criteria);
    }


    /**
     * Return an array which contains user permissions
     *
     * @param string $username
     * @return array
     * @throws \Exception
     */
    public function getUserPermissions(string $username): array
    {

        $actions = [];
        $subjects = [];
        $userPermissions = [];

        // insert only action name in array
        foreach ($this->getEntityManager()->getRepository(Action::class)->findAll() as $k => $action)
        {
            $actions[] = $action->getName();
        }

        // insert only subject name in array
        foreach ($this->getEntityManager()->getRepository(Subject::class)->findAll() as $k => $subject)
        {
            $subjects[] = $subject->getName();
        }

        // insert subject name in array as key
        foreach ($subjects as $key => $subject)
        {
            if(!array_key_exists($subject, $userPermissions))
                $userPermissions[$subject] = [];
        }




        $user = $this->findOneBy(["username" => $username]);
        if(!$user)
            throw new \Exception("User '" . $username ."' not found !");

        // permission of user
        foreach ($user->getPermission()->getValues() as $permission)
        {

            foreach ($actions as $k => $action)
            {
                if(!in_array($action, $userPermissions[$permission->getSubject()->getName()]))
                {
                    if($permission->getAction()->getName() === $action)
                        $userPermissions[$permission->getSubject()->getName()][] = $action;
                }


            }
        }


        // permission of user role
        foreach ($user->getRole()->getPermission()->getValues() as $permission)
        {

            foreach ($actions as $k => $action)
            {
                if(!is_null($permission->getSubject()))
                {
                    if(!in_array($action, $userPermissions[$permission->getSubject()->getName()]))
                    {
                        if($permission->getAction()->getName() === $action)
                            $userPermissions[$permission->getSubject()->getName()][] = $action;
                    }
                }

            }
        }

        return $userPermissions;
    }


    /**
     * Return an array which contains user permissions and permissionId
     *
     * @param string $username
     * @return array
     * @throws \Exception
     */
    public function getUserPermissionsWithId(string $username): array
    {

        $actions = [];
        $subjects = [];
        $userPermissions = [];

        // insert only action name in array
        foreach ($this->getEntityManager()->getRepository(Action::class)->findAll() as $k => $action)
        {
            $actions[] = $action->getName();
        }

        // insert only subject name in array
        foreach ($this->getEntityManager()->getRepository(Subject::class)->findAll() as $k => $subject)
        {
            $subjects[$subject->getId()] = $subject->getName();
        }

        // insert subject name in array as key
        foreach ($subjects as $key => $subject)
        {
            if(!array_key_exists($key . "_" . $subject, $userPermissions))
                $userPermissions[$key . "_" . $subject] = [];
        }

        // insert action name in array as key



        $user = $this->findOneBy(["username" => $username]);
        if(!$user)
            throw new \Exception("User '" . $username ."' not found !");

        // permission of user
        foreach ($user->getPermission()->getValues() as $permission)
        {

            foreach ($actions as $k => $action)
            {
                if(!in_array($action, $userPermissions[$permission->getSubject()->getId() . "_" . $permission->getSubject()->getName()]))
                {
                    if($permission->getAction()->getName() === $action)
                        $userPermissions[$permission->getSubject()->getId() . "_" . $permission->getSubject()->getName()][] = $action;
                }

            }
        }


        // permission of user role
        foreach ($user->getRole()->getPermission()->getValues() as $permission)
        {

            foreach ($actions as $k => $action)
            {
                if(!in_array($action, $userPermissions[$permission->getSubject()->getId() . "_" . $permission->getSubject()->getName()]))
                {
                    if($permission->getAction()->getName() === $action)
                        $userPermissions[$permission->getSubject()->getId() . "_" . $permission->getSubject()->getName()][] = $action;
                }
            }
        }

        return $userPermissions;
    }



    public function checkIfUserCanAccedeStage(User $user, string $stageName)
    {

        foreach ($user->getRole()->getStages()->getValues() as $stage)
        {
            if($stage->getName() === $stageName)
                return true;
        }

        return false;

    }


    /**
     * Check if user has specific permission (permission = action + subject)
     *
     * @param User $user
     * @param string $action
     * @param string $subject
     * @return bool
     * @throws \Exception
     */
    public function checkIfUserHasPermission(User $user, string $action, string $subject): bool
    {

        // make first character to uppercase
        $subject = ucfirst($subject);

        $permissions = $this->getUserPermissions($user->getUsername());

        if(!array_key_exists($subject, $permissions))
            return false;


        foreach ($permissions as $permissionSubject => $permissionActionArray)
        {
            if($permissionSubject === $subject)
            {
                foreach ($permissionActionArray as $key => $value)
                {
                    if($value === $action)
                        return true;
                }
            }

        }

        return false;

    }


    /**
     * Check if user has 0 permission
     *
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function checkIfUserHasZeroPermission(User $user): bool
    {

        $permissions = $this->getUserPermissions($user->getUsername());

        $i = 0;
        foreach ($permissions as $permissionSubject => $permissionActionArray)
        {
            if(empty($permissions[$permissionSubject]))
                $i++;
        }

        // 0 permissions
        if($i === sizeof($permissions))
            return true;


        return false;

    }


    // /**
    //  * @return User[] Returns an array of User objects
    //  */
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
    public function findOneBySomeField($value): ?User
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
