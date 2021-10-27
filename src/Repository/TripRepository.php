<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function findWithSearch (Search $search) {

         $query = $this->
            createQueryBuilder('t')
                ->join('t.place', 'places')
                ->join('places.city', 'cities');

        if (!empty($search->getCity())) {
            $query = $query
                ->andWhere('cities.id IN (:city)')
                ->setParameter('city', $search->getCity()[0]);
        }

        if (!empty($search->getUserSearch())) {
            $query = $query
                ->andWhere('t.tripName LIKE :string')
                ->setParameter('string',"%".$search->getUserSearch()."%");
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Trip[] Returns an array of Trip objects
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
    public function findOneBySomeField($value): ?Trip
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
