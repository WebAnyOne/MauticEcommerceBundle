<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support;

use Mautic\IntegrationsBundle\Integration\Interfaces\SyncInterface;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\MappingManualDAO;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\SyncDataExchangeInterface;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;

class SyncSupport extends PrestashopIntegration implements SyncInterface
{

    public function getMappingManual(): MappingManualDAO
    {
        // TODO: Implement getMappingManual() method.
    }

    public function getSyncDataExchange(): SyncDataExchangeInterface
    {
        // TODO: Implement getSyncDataExchange() method.
    }
}
