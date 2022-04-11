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
    private string $id;

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
        string $id,
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

    public function getContact()
    {
        return $this->contact;
    }

    public function getId(): string
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
}
