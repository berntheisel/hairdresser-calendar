<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Driver;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

    /**
     * @param string $startOfDay
     * @param string $endOfDay
     * @return array
     * @throws Driver\Exception
     * @throws Exception
     */
    public function getAllServiceTimes(string $startOfDay, string $endOfDay): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                time
            FROM
               times
            WHERE
                time >= :start_of_day AND 
                time <= :end_of_day
            ";
        $stmt = $conn->prepare($sql);
        $results = $stmt->executeQuery([
            'start_of_day' => $startOfDay,
            'end_of_day' => $endOfDay
        ])->fetchAllAssociative();

        return $results;
    }

    // /**
    //  * @return Service[] Returns an array of Service objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Service
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
