<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity;

class Transaction
{
    private $contact;

    private string $id;
    private \DateTimeImmutable $date;
    private int $price;
    private int $priceWithTaxes;
    private int $nbProducts;
    private $stat;

    public function __construct(
        $contact,
        string $id,
        \DateTimeImmutable $date,
        int $price,
        int $priceWithTaxes,
        int $nbProducts,
        $stat
    ) {
        $this->contact = $contact;
        $this->id = $id;
        $this->date = $date;
        $this->price = $price;
        $this->priceWithTaxes = $priceWithTaxes;
        $this->nbProducts = $nbProducts;
        $this->stat = $stat;
    }
}
