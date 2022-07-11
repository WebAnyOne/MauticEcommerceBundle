<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ecommerce_transaction_product")
 */
class TransactionProduct
{
    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private Transaction $transaction;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Product $product;

    /**
     * @ORM\Column(type="integer")
     */
    private int $quantity;

    public function __construct(Transaction $transaction, Product $product, int $quantity)
    {
        $this->transaction = $transaction;
        $this->product = $product;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTransaction(): Transaction
    {
        return $this->transaction;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
