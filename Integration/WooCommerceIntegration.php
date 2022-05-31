<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Integration;

use MauticPlugin\MauticEcommerceBundle\Api\ClientInterface;
use MauticPlugin\MauticEcommerceBundle\WooCommerce\Api\Client;
use MauticPlugin\MauticEcommerceBundle\WooCommerce\Form\Type\ConfigFormType;

class WooCommerceIntegration extends EcommerceAbstractIntegration
{
    public const NAME = 'WooCommerce';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getIcon(): string
    {
        return 'plugins/MauticEcommerceBundle/Assets/img/woocommerce.png';
    }

    public function getDisplayName(): string
    {
        return 'WooCommerce';
    }

    public function getAuthConfigFormName(): string
    {
        return ConfigFormType::class;
    }

    public function getClient(): ClientInterface
    {
        [
            'url' => $url,
            'consumerKey' => $consumerKey,
            'consumerSecret' => $consumerSecret,
        ] = $this->getIntegrationConfiguration()->getApiKeys();

        return new Client($url, $consumerKey, $consumerSecret);
    }
}
