<?php
namespace App\Application;

class ListProductsService
{
	public function getList($categoryId)
	{
		return 'TestList for category' . $categoryId;
	}
}