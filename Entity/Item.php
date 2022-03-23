<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity;

class Item
{
    private Transaction $transaction;
    private Product $product;
    private int $quantity;
    private \DateTimeImmutable $date;

    public function __construct(Transaction $transaction, Product $product, int $quantity, \DateTimeImmutable $date)
    {
        $this->transaction = $transaction;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->date = $date;
    }
}
