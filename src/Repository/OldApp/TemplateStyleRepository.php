<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\TemplateCssProperty;
use App\Entity\OldApp\TemplateStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method TemplateStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateStyle[]    findAll()
 * @method TemplateStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateStyle::class);
    }

    /**
     * Return last insert id, null if table is empty else return id
     */
    public function getLastInsertId()
    {

        $result = $this->createQueryBuilder('template_style')
                       ->select('template_style.id')
                       ->orderBy('template_style.id', 'DESC')
                       ->setMaxResults(1)
                       ->getQuery()
                       ->getResult();

        if($result === [])
            return null;

        else
            return $result[0]['id'];

    }

    public function getStyleByProperty(TemplateCssProperty $property)
    {
        return $this->createQueryBuilder('template_style')
                    ->where(":property MEMBER OF template_style.properties")
                    ->setParameters(['property' => $property])
                    ->getQuery()->getResult();
    }


    // /**
    //  * @return TemplateStyle[] Returns an array of TemplateStyle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemplateStyle
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
