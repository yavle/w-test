<?php
namespace App\Presentation\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Application\ListCategoriesService;
use App\Application\CategoryDTO;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

class CategoryController extends AbstractController
{
    public function __construct(private ListCategoriesService $service) {}

    #[Route('/categories')]
    public function categoryList(
        SerializerInterface $serializer,
        #[MapQueryString] CategoryDTO $categoryDTO = new CategoryDTO(),
    ): JsonResponse
    {
        return JsonResponse::fromJsonString($serializer->serialize(
            $this->service->getList($categoryDTO),
            'json',
            [
                AbstractNormalizer::ATTRIBUTES => ['id', 'name', 'path', 'is_leaf', 'parent' => ['id']]
            ]
        ));
    }
}