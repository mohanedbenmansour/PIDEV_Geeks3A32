<?php

namespace App\Repository;

use App\Entity\Tournoi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tournoi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tournoi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tournoi[]    findAll()
 * @method Tournoi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TournoiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournoi::class);

    }

    public function getPaginatedTournois($page, $limit, $filters = null){
        $query = $this->createQueryBuilder('tournoi')
            ->where('tournoi.active = 1');
        ;

        // On filtre les données
        if($filters != null){
            $query->andWhere('tournoi.category IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }

        $query->orderBy('tournoi.date_publication')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
        ;
        return $query->getQuery()->getResult();
    }

    /**
     * Returns number of Annonces
     * @return void
     */
    public function getTotalTournois($filters = null){
        $query = $this->createQueryBuilder('tournoi')
            ->select('COUNT(tournoi)')
            ->where('tournoi.active = 1');
        // On filtre les données
        if($filters != null){
            $query->andWhere('tournoi.category IN(:cats)')
                ->setParameter(':cats', array_values($filters));
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    public function findTournoiByname($nom){
        return $this->createQueryBuilder('tournoi')
            ->where('tournoi.nom LIKE :nom')
            ->setParameter('nom', '%'.$nom.'%')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Tournoi[] Returns an array of Tournoi objects
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
    public function findOneBySomeField($value): ?Tournoi
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
