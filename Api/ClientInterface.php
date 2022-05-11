<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Api;

use MauticPlugin\MauticEcommerceBundle\Model\Customer;
use MauticPlugin\MauticEcommerceBundle\Model\Order;

interface ClientInterface
{
    /**
     * @return Customer[]
     */
    public function getCustomers(int $page, int $limit = 100): array;

    /**
     * @return Order[]
     */
    public function getOrders(int $page, int $limit = 100): array;
}
