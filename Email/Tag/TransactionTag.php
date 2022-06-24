<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email\Tag;

use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;

class TransactionTag
{
    private Transaction $transaction;
    private string $tag;
    private array $params;

    public function __construct(Transaction $transaction, string $tag, array $params = [])
    {
        $this->transaction = $transaction;
        $this->tag = $tag;
        $this->params = $params;
    }

    public function getValue(): string
    {
        switch ($this->tag) {
            case 'price':
                return (string) ($this->transaction->getPriceWithTaxes() / 100);
            case 'nb_products':
                return (string) $this->transaction->getNbProducts();
            case 'date':
                $format = $this->params['format'] ?? 'd/m/Y H:i:s';

                return $this->transaction->getDate()->format($format);
            default:
                return "Unknown ({$this->tag})";
        }
    }
}
