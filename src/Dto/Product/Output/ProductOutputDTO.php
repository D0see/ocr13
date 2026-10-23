<?php

namespace App\Dto\Product\Output;

class ProductOutputDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $shortDescription,
        public string $fullDescription,
        public float $price,
        public string $picture,
    ) {
    }
}
