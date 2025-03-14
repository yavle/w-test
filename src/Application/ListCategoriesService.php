<?php
namespace App\Application;

use App\Repository\CategoryRepository;

class ListCategoriesService
{
	public function __construct(private CategoryRepository $repository) { }

	public function getList(CategoryDTO $dto)
	{
		return $this->repository->findAll($dto->page, $dto->perPage);
	}
}