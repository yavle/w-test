<?php
namespace App\Presentation\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


#[AsController]
class MarketplaceXmlController
{
    #[Route('/products.xml')]
    public function productsXml(): Response
    {
        $faker = \Faker\Factory::create();
        $categories = [];
        for ($i = 0; $i < 5000; $i++) {
            $categories[] = $faker->firstName . ' | ' . $faker->firstName . ' | ' . $faker->firstName;
        }

        $products = [];
        for ($i = 0; $i < 50000; $i++) {
            $products[] = [
                'title' => 'title' . $i,
                'description' => 'description' . $i,
                'category' => $categories[array_rand($categories)],
                'sku' => random_int(100, 999) . '/' . random_int(10,99),
                'price' => random_int(0, 10000) . '.' . random_int(0, 99), 
                'stock' => random_int(0, 10000),
            ];
        } 

        $xml_data = new \SimpleXMLElement('<?xml version="1.0"?><products></products>');
        self::array_to_xml($products, $xml_data);

        $response = new Response($xml_data->asXML());

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'products.xml'
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    public static function array_to_xml( $data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) ){
                    $key = 'item'.$key; //dealing with <0/>..<n/> issues
                }
                $subnode = $xml_data->addChild('product');
                self::array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
}


