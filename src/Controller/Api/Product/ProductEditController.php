<?php

namespace App\Controller\Api\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductEditController extends AbstractController
{
    private $productRepository;
    private $errors = [];

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function __invoke(Product $data)
    {
        $product = $this->productRepository->findOneBy(['name' => $data->getName()]);
       
        if ($product && $product->getId() !== $data->getId()) {
            $this->errors['product_exist'] = 'This product already exists';
            return $this->json(['errors' => $this->errors], 422);
        }
        return $data;
    }
}
