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

    /** @var OrderProduct[] */
    public array $products;

    public function __construct(
        int $id,
        int $customerId,
        \DateTimeImmutable $date,
        int $priceWithoutTaxes,
        int $priceWithTaxes,
        int $nbProducts,
        array $products
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->priceWithoutTaxes = $priceWithoutTaxes;
        $this->priceWithTaxes = $priceWithTaxes;
        $this->nbProducts = $nbProducts;
        $this->customerId = $customerId;
        $this->products = $products;
    }
}
