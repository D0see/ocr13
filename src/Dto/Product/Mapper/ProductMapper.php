<?php

namespace App\Dto\Product\Mapper;

use App\Dto\Product\Output\ProductOutputDTO;
use App\Entity\Product;

class ProductMapper
{
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
