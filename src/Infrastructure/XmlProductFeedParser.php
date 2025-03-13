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
        
        $products = [];
        
        foreach ($xml->product as $productXml) {    
            $products[] = [
                'title' => (string) $productXml->title,
                'description' => (string) $productXml->description,
                'sku' => (string) $productXml->sku,
                'price' => (float) $productXml->price,
                'stock' => (int) $productXml->stock,
                'category' => (string) $productXml->category,
            ];
        }
        
        return $products;
    }
}
