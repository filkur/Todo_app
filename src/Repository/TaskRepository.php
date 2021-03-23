<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
     * @param $categoryId
     * @param $userId
     *
     * @return Task[] Returns an array of Task object
     */
    public function findAllByCategoryIdAndUserId($categoryId, $userId)
    {
        return $this->getEntityManager()
                    ->createQuery(
                        'SELECT t FROM App\Entity\Task t WHERE t.category = :categoryId AND t.user = :userId'
                    )
                    ->setParameter('categoryId', $categoryId)
                    ->setParameter('userId', $userId)
                    ->getResult()
            ;
    }

    public function findById($id)
    {
        try {
            return $this->getEntityManager()
                        ->createQuery(
                            'SELECT t FROM App\Entity\Task t WHERE t.id = :id'
                        )
                        ->setParameter('id', $id)
                        ->getOneOrNullResult()
                ;
        } catch (NonUniqueResultException $e) {
            $e->getMessage();
        }
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

    /*
    public function findOneBySomeField($value): ?Task
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
