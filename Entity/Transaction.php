<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="transactions")
 */
class Transaction
{
    /**
     * @ORM\Column(type="integer", nullable=false, name="lead_id")
     */
    private $leadId;

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
        $leadId,
        int $id,
        \DateTimeImmutable $date,
        int $priceWithoutTaxes,
        int $priceWithTaxes,
        int $nbProducts
    ) {
        $this->leadId = $leadId;
        $this->id = $id;
        $this->date = $date;
        $this->priceWithoutTaxes = $priceWithoutTaxes;
        $this->priceWithTaxes = $priceWithTaxes;
        $this->nbProducts = $nbProducts;
    }

    public function getLeadId()
    {
        return $this->leadId;
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

    public static function fromOrderArray(string $leadId, array $order): self
    {
        return new self(
            $leadId,
            $order['id'],
            new \DateTimeImmutable($order['date_add']),
            (int) ((float)$order['total_paid_tax_excl'] * 100),
            (int) ((float)$order['total_paid_tax_incl'] * 100),
            count($order['associations']['order_rows'])
        );
    }
}
