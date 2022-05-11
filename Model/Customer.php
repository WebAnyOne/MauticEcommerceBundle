<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Model;

class Customer
{
    public string $id;
    public string $email;
    public string $firstName;
    public string $lastName;
    public \DateTimeImmutable $createdAt;
    public \DateTimeImmutable $updatedAt;

    public function __construct(
        string $id,
        string $email,
        string $firstName,
        string $lastName,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
