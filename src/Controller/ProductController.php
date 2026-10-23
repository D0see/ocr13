<?php

namespace App\Controller;

use App\Dto\Product\Mapper\ProductMapper;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product')]
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductMapper $productMapper,
    ) {
    }

    #[Route('/{id}', name: 'product_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new NotFoundHttpException('Product not found.');
        }

        return $this->render('product/detail.html.twig', [
            'product' => $this->productMapper->productToFocusOutputDTO($product),
        ]);
    }
}
