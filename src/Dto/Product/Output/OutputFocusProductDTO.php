<?php

namespace App\Dto\Product\Output;

class OutputFocusProductDTO
{
    public function __construct(
        public string $picture,
        public string $name,
        public float $price,
        public string $fullDescription,
    ) {
    }
}
