<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Api;

interface ClientInterface
{
    public function getCustomers(int $page, int $limit): array;
}
