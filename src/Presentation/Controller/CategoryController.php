<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController
{
    #[Route('/categories')]
    public function categoryList(): Response
    {
        return new Response(
            ['Cat1', 'Cat2']
        );
    }
}