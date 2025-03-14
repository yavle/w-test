<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @return Category[]
     */
    public function findAll(int $page = 1, int $perPage = 500): array
    {
        $offset = ($page - 1) * $perPage;

        return $this->createQueryBuilder('c')
            ->orderBy('c.path', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult();
    }

    public function findOneByPath($value): ?Category
    {
       return $this->createQueryBuilder('c')
           ->andWhere('c.path = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
    }

    public function deleteAllRecords(){
        return $this->createQueryBuilder('e')
                 ->delete()
                 ->getQuery()
                 ->execute();
    }
}
