<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity=TransactionProduct::class, mappedBy="transaction", indexBy="product", cascade={"all"}, orphanRemoval=true)
     *
     * @var Collection<int, TransactionProduct>
     */
    private Collection $products;

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

        $this->products = new ArrayCollection();
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

    public function addProduct(Product $product, int $quantity): void
    {
        $this->products->set($product->getId(), new TransactionProduct($this, $product, $quantity));
    }

    /**
     * @return Collection<int, TransactionProduct>
     */
    public function getProducts()
    {
        return $this->products;
    }

    public function update(Transaction $transaction): void
    {
        $this->date = $transaction->date;
        $this->priceWithoutTaxes = $transaction->priceWithoutTaxes;
        $this->priceWithTaxes = $transaction->priceWithTaxes;
        $this->nbProducts = $transaction->nbProducts;

        $this->products = new ArrayCollection();

        /** @var TransactionProduct $product */
        foreach ($transaction->products as $product) {
            $this->addProduct($product->getProduct(), $product->getQuantity());
        }
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
