<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController
{
    #[Route('/categories/{category_id}/products')]
    public function productList($categoryId): Response
    {
        return new Response(
            'Product1', 'Product2'
        );
    }
}