<?php
namespace App\Application;
use App\Entity\Category;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ListProductsService
{
	public function __construct(
		private ProductRepository $repository,
		private EntityManagerInterface $entityManager
	) { }

	public function getList(Category $category, ProductDTO $dto)
	{
		return $this->repository->findByCategory(
			$category,
			$dto->page,
			$dto->perPage,
			$dto->minPrice,
			$dto->maxPrice,
			$dto->exclude,
		);
	}
}