<?php
namespace App\Presentation\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

use App\Application\ListCategoriesService;

#[AsController]
class CategoryController
{
    public function __construct(private ListCategoriesService $service) {}

    #[Route('/categories')]
    public function categoryList(): Response
    {
        return new Response(
            $this->service->getList()
        );
    }
}