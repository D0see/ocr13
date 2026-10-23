<?php

namespace App\Controller;

use App\Dto\Product\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductMapper $productMapper,
    ) {
    }

    #[Route('/home', name: 'home', methods: ['GET'])]
    public function home(): Response
    {
        $products = array_map(
            $this->productMapper->productToHomeProductDTO(...),
            $this->productRepository->findAll(),
        );

        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }
}
