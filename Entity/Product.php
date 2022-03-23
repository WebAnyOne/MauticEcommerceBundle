<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity;

class Product
{
    private string $productId;
    private Category $category;
    private string $name;
    private string $sku;
    private int $sellPrice;
    private string $description;
    private string $url;

    public function __construct(
        string $productId,
        Category $category,
        string $name,
        string $sku,
        int $sellPrice,
        string $description,
        string $url
    ) {
        $this->productId = $productId;
        $this->category = $category;
        $this->name = $name;
        $this->sku = $sku;
        $this->sellPrice = $sellPrice;
        $this->description = $description;
        $this->url = $url;
    }
}
