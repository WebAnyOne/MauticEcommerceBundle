<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Templating\Helper;

use NumberFormatter;
use Symfony\Component\Templating\Helper\Helper;

class MoneyHelper extends Helper
{
    public function format(int $value, string $currency = 'EUR'): string
    {
        $formatter = new NumberFormatter(\Locale::getDefault(), NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($value / 100, $currency);
    }

    public function getName()
    {
        return 'ecommerce_money';
    }
}
