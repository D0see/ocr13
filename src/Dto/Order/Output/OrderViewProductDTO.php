<?php

namespace App\Dto\Order\Output;

class OrderViewProductDTO
{
    public function __construct(
        public string $name,
        public string $picture,
        public int $quantity,
        public float $unitPrice,
        public float $totalPrice,
    ) {
    }
}
