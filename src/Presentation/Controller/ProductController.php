<?php
namespace App\Presentation\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Application\ListProductsService;
use App\Application\ProductDTO;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

use App\Entity\Category;

class ProductController extends AbstractController
{
    public function __construct(private ListProductsService $service) {}

    #[Route('/categories/{id}/products')]
    public function productList(
        Category $category,
        SerializerInterface $serializer,
        #[MapQueryString] ProductDTO $productDTO,
    ): JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $this->service->getList($category, $productDTO),
            'json',
            [
                AbstractNormalizer::ATTRIBUTES => ['id', 'title', 'price', 'sku', 'description', 'stock']
            ]
        ));
    }
}