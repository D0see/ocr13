<?php

namespace App\Controller;

use App\Dto\Order\Mapper\OrderMapper;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly OrderMapper $orderMapper
    ){}

    #[Route('/active-order', name: 'order_active', methods: ['GET'])]
    public function activeOrder(): Response
    {

        $order = $this->orderService->findActiveOrder();

        return $this->render('order/active_order.html.twig', [
            'order' => $this->orderMapper->orderToOrderViewDTO($order),
        ]);
    }
}
