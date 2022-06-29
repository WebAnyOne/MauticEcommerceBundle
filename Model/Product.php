<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Model;

class Product
{
    public int $id;
    public string $name;
    public int $unitPrice;
    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $updatedAt;

    public function __construct(
        int $id,
        string $name,
        int $unitPrice,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->unitPrice = $unitPrice;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
