<?php

namespace App\Repository;

use App\Entity\AdminReponseDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminReponseDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminReponseDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminReponseDevis[]    findAll()
 * @method AdminReponseDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminReponseDevisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminReponseDevis::class);
    }

    // /**
    //  * @return AdminReponseDevis[] Returns an array of AdminReponseDevis objects
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
    public function findOneBySomeField($value): ?AdminReponseDevis
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
