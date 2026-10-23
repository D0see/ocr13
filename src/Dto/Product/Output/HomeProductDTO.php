<?php

namespace App\Dto\Product\Output;

class HomeProductDTO
{
    public function __construct(
        public string $picture,
        public string $name,
        public string $shortDescription,
        public float $price,
    ) {
    }
}
