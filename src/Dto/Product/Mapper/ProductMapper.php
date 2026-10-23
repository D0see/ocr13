<?php

namespace App\Dto\Product\Mapper;

use App\Dto\Product\Output\ProductOutputDTO;
use App\Dto\Product\Output\OutputFocusProductDTO;
use App\Dto\Product\Output\HomeProductDTO;
use App\Entity\Product;

class ProductMapper
{
    public function productToHomeProductDTO(Product $product): HomeProductDTO
    {
        $name = $product->getName();
        $shortDescription = $product->getShortDescription();
        $price = $product->getPrice();
        $picture = $product->getPicturePath();

        if ($name === null || $shortDescription === null || $price === null || $picture === null) {
            throw new \LogicException('Cannot map an incomplete product.');
        }

        return new HomeProductDTO(
            picture: $picture,
            name: $name,
            shortDescription: $shortDescription,
            price: $price,
        );
    }

    public function productToOutputFocusProductDTO(Product $product): OutputFocusProductDTO
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
