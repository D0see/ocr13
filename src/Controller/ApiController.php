<?php

namespace App\Controller;

use App\Dto\Product\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ApiController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductMapper $productMapper,
    ) {
    }

    // #[Route('/login', name: 'api_login', methods: ['POST'])]
    // public function login(): never
    // {
    //     throw new \LogicException('Login requests must be handled by the JSON login firewall.');
    // }

    #[Route('/products', name: 'api_products', methods: ['GET'])]
    public function products(): JsonResponse
    {
        $products = array_map(
            $this->productMapper->productToOutputDTO(...),
            $this->productRepository->findAll(),
        );

        return $this->json($products);
    }
}
