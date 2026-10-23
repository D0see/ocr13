<?php

namespace App\Dto\Order\Output;

class OrderViewDTO
{
    /**
     * @param list<OrderViewProductDTO> $products
     */
    public function __construct(
        public array $products = [],
    ) {
    }
}
