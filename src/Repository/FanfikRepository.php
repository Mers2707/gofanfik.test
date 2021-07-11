<?php

namespace App\Repository;

use App\Entity\Fanfik;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fanfik|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fanfik|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fanfik[]    findAll()
 * @method Fanfik[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FanfikRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fanfik::class);
    }

    // /**
    //  * @return Fanfik[] Returns an array of Fanfik objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Fanfik
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
