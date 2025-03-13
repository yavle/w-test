<?php
namespace App\Application;

use Doctrine\ORM\EntityManagerInterface;

use App\Infrastructure\XmlProductFeedParser;
use App\Entity\Product;
use App\Entity\Category;

class ImportXmlProductFeedService
{
	public function __construct(
		private XmlProductFeedParser $parser,
		private EntityManagerInterface $entityManager
	) {}

	public function loadFeed($url)
	{
		$category = new Category();
		$category->setName('Root Category');
		$category->setLevel(0);
		$this->entityManager->persist($category);
        $this->entityManager->flush();

		$products = $this->parser->parse($url);


        $batchCounter = 0;
        $batchSize = 250;
        $batchProducts = [];
		foreach ($products as $proto) {
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
        	$this->entityManager->persist($product);
        	if ($batchCounter++ >= $batchSize) {
        		$this->entityManager->flush();
        		$batchProducts = [];
        		$batchCounter = 0;
        		echo('+');
        	}
		}
		
		// print_r($categories);
		return true;
	}
}