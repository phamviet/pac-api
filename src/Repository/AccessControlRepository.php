<?php

namespace App\Repository;

use App\Entity\AccessControl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AccessControl|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessControl|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessControl[]    findAll()
 * @method AccessControl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessControlRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AccessControl::class);
    }

    // /**
    //  * @return AccessControl[] Returns an array of AccessControl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccessControl
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
