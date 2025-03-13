<?php

namespace App\Infrastructure;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class XmlProductFeedParser
{
    public function __construct(private HttpClientInterface $client)
    {}

    /**
     * Parse feed from the given URL
     * @return array An array containing categories and products
     */
    public function parse(string $url): array
    {
        $response = $this->client->request('GET', $url);
        $content = $response->getContent();
        
        $xml = new \SimpleXMLElement($content);
        
        // $categories = [];
        $products = [];
        
        foreach ($xml->product as $productXml) {
            // $categoryPath = (string) $productXml->category;
            
            // Add category if it doesn't exist
            // if (!isset($categories[$categoryPath])) {
            //     $categories[$categoryPath] = [
            //         'path' => $categoryPath,
            //         'categories' => $this->parseCategoryPath($categoryPath)
            //     ];
            // }
            
            $products[] = [
                'title' => (string) $productXml->title,
                'description' => (string) $productXml->description,
                'sku' => (string) $productXml->sku,
                'price' => (float) $productXml->price,
                'stock' => (int) $productXml->stock,
                // 'category_path' => $categoryPath
                'category' => (string) $productXml->category,
            ];
        }
        
        return $products;
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
