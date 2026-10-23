<?php

namespace App\Dto\Product\Mapper;

use App\Dto\Order\Output\OrderViewProductDTO;
use App\Dto\Product\Output\ProductOutputDTO;
use App\Dto\Product\Output\OutputFocusProductDTO;
use App\Entity\OrderProduct;
use App\Entity\Product;

class ProductMapper
{
    public function orderProductToOrderViewProductDTO(OrderProduct $orderProduct): OrderViewProductDTO
    {
        $product = $orderProduct->getProduct();
        $quantity = $orderProduct->getQuantity();

        if ($product === null || $quantity === null) {
            throw new \LogicException('Cannot map an incomplete order product.');
        }

        $name = $product->getName();
        $picture = $product->getPicturePath();
        $unitPrice = $product->getPrice();

        if ($name === null || $picture === null || $unitPrice === null) {
            throw new \LogicException('Cannot map an order product with incomplete product details.');
        }

        return new OrderViewProductDTO(
            name: $name,
            picture: $picture,
            quantity: $quantity,
            unitPrice: $unitPrice,
            totalPrice: $unitPrice * $quantity,
        );
    }

    public function productToFocusOutputDTO(Product $product): OutputFocusProductDTO
    {
        $name = $product->getName();
        $price = $product->getPrice();
        $fullDescription = $product->getFullDescription();
        $picture = $product->getPicturePath();

        if ($name === null || $price === null || $fullDescription === null || $picture === null) {
            throw new \LogicException('Cannot map an incomplete product.');
        }

        return new OutputFocusProductDTO(
            picture: $picture,
            name: $name,
            price: $price,
            fullDescription: $fullDescription,
        );
    }

    public function productToOutputDTO(Product $product): ProductOutputDTO
    {
        $id = $product->getId();
        $name = $product->getName();
        $shortDescription = $product->getShortDescription();
        $fullDescription = $product->getFullDescription();
        $price = $product->getPrice();
        $picture = $product->getPicturePath();

        if (
            $id === null
            || $name === null
            || $shortDescription === null
            || $fullDescription === null
            || $price === null
            || $picture === null
        ) {
            throw new \LogicException('Cannot map an incomplete product.');
        }

        return new ProductOutputDTO(
            id: $id,
            name: $name,
            shortDescription: $shortDescription,
            fullDescription: $fullDescription,
            price: $price,
            picture: $picture,
        );
    }
}
