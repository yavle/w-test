<?php
namespace App\Application;
use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    public function __construct(
        #[Assert\PositiveOrZero]
        public int $page = 1,

        #[Assert\Positive]
        public int $perPage = 20,

        #[Assert\PositiveOrZero]
        public float $minPrice = 0,

        #[Assert\PositiveOrZero]
        public $maxPrice = null,

        #[Assert\Boolean]
        public bool $exclude = false,
    ) {
    }
}