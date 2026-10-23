<?php

namespace App\Dto\Order\Mapper;

use App\Dto\Order\Output\OrderViewDTO;
use App\Dto\Order\Output\OrderViewProductDTO;
use App\Entity\Order;
use App\Entity\OrderProduct;

class OrderMapper
{
    public function orderToOrderViewDTO(Order $order): OrderViewDTO
    {
        $products = array_map(
            $this->orderProductToOrderViewProductDTO(...),
            $order->getOrderProducts()->toArray(),
        );

        return new OrderViewDTO(products: $products);
    }

    public function orderProductToOrderViewProductDTO(OrderProduct $orderProduct): OrderViewProductDTO
    {
        $product = $orderProduct->getProduct();
        $quantity = $orderProduct->getQuantity();

        if ($product === null || $quantity === null) {
            throw new \LogicException('Cannot map an incomplete order product.');
        }

        $name = $product->getName();
        $picture = $product->getPicturePath();
        $price = $product->getPrice();

        if ($name === null || $picture === null || $price === null) {
            throw new \LogicException('Cannot map an order product with incomplete product details.');
        }

        return new OrderViewProductDTO(
            name: $name,
            picture: $picture,
            price: $price,
            quantity: $quantity,
        );
    }
}
