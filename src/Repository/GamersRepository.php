<?php

namespace App\Repository;

use App\Entity\Gamers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Gamers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gamers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gamers[]    findAll()
 * @method Gamers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gamers::class);
    }

    // /**
    //  * @return Gamers[] Returns an array of Gamers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Gamers
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
