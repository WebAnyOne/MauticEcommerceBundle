<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity;

class Category
{
    private string $categoryId;
    private string $name;

    public function __construct(string $categoryId, string $name)
    {
        $this->categoryId = $categoryId;
        $this->name = $name;
    }
}
