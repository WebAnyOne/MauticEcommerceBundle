<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Model;

class Order
{
    public int $id;
    public int $customerId;
    public \DateTimeImmutable $date;
    public int $priceWithoutTaxes;
    public int $priceWithTaxes;
    public int $nbProducts;

    public function __construct(
        int $id,
        int $customerId,
        \DateTimeImmutable $date,
        float $priceWithoutTaxes,
        float $priceWithTaxes,
        int $nbProducts
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->priceWithoutTaxes = $priceWithoutTaxes;
        $this->priceWithTaxes = $priceWithTaxes;
        $this->nbProducts = $nbProducts;
        $this->customerId = $customerId;
    }
}
