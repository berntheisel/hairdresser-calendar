<?php

namespace App\Repository;

use App\Entity\BookingsServices;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BookingsServices|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingsServices|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingsServices[]    findAll()
 * @method BookingsServices[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingsServicesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookingsServices::class);
    }

    // /**
    //  * @return BookingsServices[] Returns an array of BookingsServices objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BookingsServices
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
