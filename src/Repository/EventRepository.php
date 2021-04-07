<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     *
     * @return void
     */
    public function search($mots=null, $category=ull) {
        $now = new \DateTime();
        $query = $this->createQueryBuilder('e');
        $query->where('e.dateDebut > :now')
            ->setParameter(':now', $now)->orderBy('e.dateDebut');
        if($mots != null){
            $query->andWhere('MATCH_AGAINST (e.name,e.description,e.lieu) AGAINST (:mots boolean)>0')
                ->setParameter('mots', $mots);
        }
        if($category != null){
            $query->leftJoin('e.Category', 'c');
            $query->andWhere('c.id = :id')
                ->setParameter('id',$category);
        }
        return $query->getQuery()->getResult();
    }


    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
