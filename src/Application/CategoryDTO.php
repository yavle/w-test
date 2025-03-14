<?php
namespace App\Application;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryDTO
{
    public function __construct(
        #[Assert\PositiveOrZero]
        public int $page = 1,

        #[Assert\Positive]
        public int $perPage = 20,
    ) {
    }
}