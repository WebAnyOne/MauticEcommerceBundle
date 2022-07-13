<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email\Tag;

use MauticPlugin\MauticEcommerceBundle\Entity\TransactionProduct;

class TransactionProductTag
{
    private TransactionProduct $transactionProduct;
    private string $tag;

    /** @phpstan-ignore-next-line Ignored for now as we don't need it */
    private array $params;

    public function __construct(TransactionProduct $transactionProduct, string $tag, array $params = [])
    {
        $this->transactionProduct = $transactionProduct;
        $this->tag = $tag;
        $this->params = $params;
    }

    public function getValue(): string
    {
        switch ($this->tag) {
            case 'quantity':
                return (string) $this->transactionProduct->getQuantity();
            case 'name':
                return $this->transactionProduct->getProduct()->getName();
            case 'unit_price':
                return (string) ($this->transactionProduct->getProduct()->getUnitPrice() / 100);
            default:
                return "Unknown ({$this->tag})";
        }
    }
}
