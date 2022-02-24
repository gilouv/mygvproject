<?php

namespace App\Repository;

use App\Entity\CommandShop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandShop|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandShop|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandShop[]    findAll()
 * @method CommandShop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandShop::class);
    }

    // /**
    //  * @return CommandShop[] Returns an array of CommandShop objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandShop
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
