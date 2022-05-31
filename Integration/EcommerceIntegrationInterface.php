<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Integration;

use MauticPlugin\MauticEcommerceBundle\Api\ClientInterface;

interface EcommerceIntegrationInterface
{
    public function getClient(): ClientInterface;
}
