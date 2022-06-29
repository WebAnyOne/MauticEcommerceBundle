<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Model;

class OrderProduct
{
    public int $productId;
    public int $quantity;

    public function __construct(int $productId, int $quantity)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
    }
}
