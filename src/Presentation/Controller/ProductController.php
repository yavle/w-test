<?php
namespace App\Presentation\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Application\ListProductsService;

#[AsController]
class ProductController
{
    public function __construct(private ListProductsService $service) {}

    #[Route('/categories/{category_id}/products')]
    public function productList(int $category_id): Response
    {
        return new Response(
            $this->service->getList($category_id)
        );
    }
}