<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle;

interface ClientInterface
{
    public function getCustomers(int $page, int $limit): array;
}
