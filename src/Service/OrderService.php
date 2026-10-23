<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class OrderService {
    public function __construct(
        private readonly OrderProductRepository $orderProductRepository,
        private readonly OrderStatusRepository $orderStatusRepository,
        private readonly OrderRepository $orderRepository,
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager
    ){}

    public function updateProductQuantityInActiveOrder(
        Product $product,
        int $quantity
    ): Order {

        $order = $this->findActiveOrder(); 

        $existingOrderProduct = $this->orderProductRepository->findOneBy(['linkedOrderId' => $order->getId()]);

        if ($quantity <= 0) {

            if ($existingOrderProduct !== null) {
                $this->entityManager->remove($existingOrderProduct);
                $this->entityManager->flush();
            }

            return $order;

        } else if ($existingOrderProduct === null) {

            $existingOrderProduct = (new OrderProduct())
            ->setLinkedOrder($order)
            ->setProduct($product)
            ->setQuantity($quantity);

        } else {

            $existingOrderProduct->setQuantity($quantity);

        }

        $this->entityManager->persist($existingOrderProduct);

        $this->entityManager->flush();

        return $order;
        
    }

    public function completeActiveOrder() {
       
        $order = $this->findActiveOrder();

        $order->setStatus($this->orderStatusRepository->findOneBy(['label' => 'COMPLETED']));
        $order->setValidatedAt(new \DateTimeImmutable());

        $newOrder = (new Order())
        ->setUser($this->security->getUser())
        ->setStatus($this->orderStatusRepository->findOneBy(['label' => 'PENDING']));

        $this->entityManager->persist($newOrder);

        $this->entityManager->flush();
    }

    public function findActiveOrder(): Order {

        /**
         * @var User $user
         */
        $user = $this->security->getUser();

        $status = $this->orderStatusRepository->findOneBy(['label' => 'PENDING']);

        return $this->orderRepository->findOneBy(
            [
                'status' => $status,
                'userId' => $user->getId()
            ]
        );
    }
}