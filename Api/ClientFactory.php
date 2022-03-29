<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Api;

use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;

class ClientFactory
{
    private IntegrationsHelper $integrationsHelper;

    public function __construct(IntegrationsHelper $integrationsHelper)
    {
        $this->integrationsHelper = $integrationsHelper;
    }

    public function getClient(): ClientInterface
    {
        $configuration = $this->integrationsHelper->getIntegration(PrestashopIntegration::NAME)->getIntegrationConfiguration();

        $apiKeys = $configuration->getApiKeys();

        return new Client($apiKeys['url'], $apiKeys['token']);
    }
}
