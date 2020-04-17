<?php

namespace App\Repository;

use App\Entity\ExchangeOffers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExchangeOffers|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeOffers|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeOffers[]    findAll()
 * @method ExchangeOffers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeOffersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeOffers::class);
    }

    // /**
    //  * @return ExchangeOffers[] Returns an array of ExchangeOffers objects
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

    public function getOfferList($user)
    {
        return $this->createQueryBuilder('e')
            ->select("e.id, e.description, e.respect_points, e.status")
            ->andWhere('e.to_user = :user')
            ->andWhere('e.status = :status')
            ->addSelect('u.name as username')
            ->leftJoin('e.from_user', 'u')
            ->setParameter('user', $user)
            ->setParameter('status', 'Requested')
            ->orderBy('e.created_at', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
    
    public function getRequestList($user)
    {
        return $this->createQueryBuilder('e')
            ->select("e.id, e.description, e.respect_points, e.status")
            ->andWhere('e.from_user = :user')
            ->addSelect('u.name as username')
            ->leftJoin('e.to_user', 'u')
            ->setParameter('user', $user)
            ->orderBy('e.created_at', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
    
}
