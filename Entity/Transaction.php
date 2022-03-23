<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity;

class Transaction
{
    private $contact;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $purchaseAt;

    public function __construct($contact, \DateTimeImmutable $createdAt, ?\DateTimeImmutable $purchaseAt)
    {
        $this->contact = $contact;
        $this->createdAt = $createdAt;
        $this->purchaseAt = $purchaseAt;
    }
}
