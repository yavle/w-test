<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @return Product[]
     */
    public function findByCategory(
        Category $category,
        int $page = 1,
        int $perPage = 500,
        float $minPrice = 0,
        float|null $maxPrice = null,
        bool $exclude = false
    ): array
    {
        $offset = ($page - 1) * $perPage;
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.category = :category')
            ->setParameter('category', $category)
            ->andWhere('p.price >= :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->orderBy('p.sku', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($perPage);
        if (!is_null($maxPrice)) {
            $queryBuilder
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }
        if ($exclude) {
            $queryBuilder->andWhere('p.stock > 0');
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function findOneBySku($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sku = :val')
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
