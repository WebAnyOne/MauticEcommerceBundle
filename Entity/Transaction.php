<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Model\Order;

/**
 * @ORM\Entity
 * @ORM\Table(name="ecommerce_transaction")
 */
class Transaction
{
    /**
     * @ORM\ManyToOne(targetEntity=Lead::class)
     */
    private Lead $lead;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=false)
     */
    private \DateTimeImmutable $date;

    /**
     * @ORM\Column(type="integer")
     */
    private int $priceWithoutTaxes;

    /**
     * @ORM\Column(type="integer")
     */
    private int $priceWithTaxes;

    /**
     * @ORM\Column(type="integer")
     */
    private int $nbProducts;

    public function __construct(
        Lead $lead,
        int $id,
        \DateTimeImmutable $date,
        int $priceWithoutTaxes,
        int $priceWithTaxes,
        int $nbProducts
    ) {
        $this->lead = $lead;
        $this->id = $id;
        $this->date = $date;
        $this->priceWithoutTaxes = $priceWithoutTaxes;
        $this->priceWithTaxes = $priceWithTaxes;
        $this->nbProducts = $nbProducts;
    }

    public function getLead()
    {
        return $this->lead;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getPriceWithoutTaxes(): int
    {
        return $this->priceWithoutTaxes;
    }

    public function getPriceWithTaxes(): int
    {
        return $this->priceWithTaxes;
    }

    public function getNbProducts(): int
    {
        return $this->nbProducts;
    }

    public function update(Transaction $transaction): void
    {
        $this->date = $transaction->date;
        $this->priceWithoutTaxes = $transaction->priceWithoutTaxes;
        $this->priceWithTaxes = $transaction->priceWithTaxes;
        $this->nbProducts = $transaction->nbProducts;
    }

    public static function fromOrder(Lead $lead, Order $order): self
    {
        return new self(
            $lead,
            $order->id,
            $order->date,
            $order->priceWithoutTaxes,
            $order->priceWithTaxes,
            $order->nbProducts,
        );
    }
}
