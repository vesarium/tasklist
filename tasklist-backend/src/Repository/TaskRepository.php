<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    // /**
    //  * @return Task[] Returns an array of Task objects
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

   public function getTasksList($user) {
        return $this->createQueryBuilder('t')
            ->select("t.id, t.title, t.description, t.due_date, t.respect_points, t.status")
            ->andWhere("t.user != ".$user)
            ->andWhere("t.deletedAt IS NULL")
            ->addSelect('u.name as user')
            ->addSelect('u1.name as accepted_by, u1.id as accepted_by_userId')
            ->leftJoin('t.user', 'u')
            ->leftJoin('t.accepted_by', 'u1')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;
   }
    
    public function getUsersTasksList($user)
    {
        return $this->createQueryBuilder('t')
            ->select("t.id, t.title, t.description, t.due_date, t.respect_points, t.status")
            ->andWhere("t.user = ".$user)
            ->andWhere("t.deletedAt IS NULL")
            ->addSelect('u.name as user')
            ->addSelect('u1.name as accepted_by, u1.id as accepted_by_userId')
            ->leftJoin('t.user', 'u')
            ->leftJoin('t.accepted_by', 'u1')
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;
    }
    
}
