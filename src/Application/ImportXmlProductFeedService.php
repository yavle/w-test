<?php
namespace App\Application;

use Doctrine\ORM\EntityManagerInterface;

use App\Infrastructure\XmlProductFeedParser;
use App\Entity\Product;
use App\Entity\Category;

use function Symfony\Component\String\u;

class ImportXmlProductFeedService
{
    public function __construct(
        private XmlProductFeedParser $parser,
        private EntityManagerInterface $entityManager
    ) {}

    public function loadFeed($url, $batchSize)
    {
        $products = $this->parser->parse($url);

        $batchCounter = 0;
        $batchProducts = [];
        $batchCategories = [];
        foreach ($products as $proto) {
            $categoryNames = explode('|', $proto['category']);
            $parentCategory = null;
            $path = '';
            foreach($categoryNames as $level => $categoryName) {
                $categoryName = u($categoryName)->trim();
                $path = $level === 0 ? (String)$categoryName : ($path . '|' . $categoryName);
                $category = $batchCategories[$path]
                    ?? 
                    $this->entityManager
                        ->getRepository(Category::class)
                        ->findOneByPath($path);

                if (is_null($category)) {
                    $category = new Category();
                    $category->setName($categoryName);
                    $category->setIsLeaf($level === count($categoryNames) - 1);
                    $category->setParent($parentCategory);
                    $category->setPath($path);
                    $batchCategories[$path] = $category;
                    $this->entityManager->persist($category);
                    if ($parentCategory) {
                        $this->entityManager->persist($parentCategory);
                    }
                }

                $parentCategory = $category;
            }

            $sku = $proto['sku'];
            $product = 
                $batchProducts[$sku]
                ?? 
                $this->entityManager->getRepository(Product::class)->findOneBySku($sku)
                ??
                new Product()
            ;
            
            $product->setTitle($proto['title']);
            $product->setDescription($proto['description']);
            $product->setSku($sku);
            $product->setPrice($proto['price']);
            $product->setStock($proto['stock']);
            $product->setCategory($category);
            
            $batchProducts[$sku] = $product;
            $this->entityManager->persist($category);
            $this->entityManager->persist($product);

            if ($batchCounter++ >= $batchSize) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                $batchProducts = [];
                $batchCategories = [];
                $batchCounter = 0;
                echo("+");
            }
        }
        
        return true;
    }

    /**
     * Parse category path into an array of category names
     * @return array
     */
    private function parseCategoryPath(string $path): array
    {
        $categories = explode(' | ', $path);
        $result = [];
        $currentPath = '';
        
        foreach ($categories as $index => $categoryName) {
            $currentPath = $index === 0 ? $categoryName : $currentPath . ' | ' . $categoryName;
            $result[] = [
                'name' => $categoryName,
                'path' => $currentPath,
                'level' => $index,
                'is_leaf' => ($index === count($categories) - 1)
            ];
        }
        
        return $result;
    }
}