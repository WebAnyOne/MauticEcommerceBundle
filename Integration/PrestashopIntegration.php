<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Integration;

use MauticPlugin\MauticEcommerceBundle\Api\ClientInterface;
use MauticPlugin\MauticEcommerceBundle\Prestashop\Api\Client;
use MauticPlugin\MauticEcommerceBundle\Prestashop\Form\Type\ConfigFormType;

class PrestashopIntegration extends EcommerceAbstractIntegration
{
    public const NAME = 'Prestashop';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getIcon(): string
    {
        return 'plugins/MauticEcommerceBundle/Assets/img/prestashop.png';
    }

    public function getDisplayName(): string
    {
        return 'Prestashop';
    }

    public function getAuthConfigFormName(): string
    {
        return ConfigFormType::class;
    }

    public function getClient(): ClientInterface
    {
        ['url' => $url, 'token' => $token] = $this->getIntegrationConfiguration()->getApiKeys();

        return new Client($url, $token);
    }
}
