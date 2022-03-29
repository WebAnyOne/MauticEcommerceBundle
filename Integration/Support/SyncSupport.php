<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support;

use Mautic\IntegrationsBundle\Integration\Interfaces\SyncInterface;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\MappingManualDAO;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\SyncDataExchangeInterface;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\DataExchange\SyncDataExchange;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\Mapping\Manual\MappingManualFactory;

class SyncSupport extends PrestashopIntegration implements SyncInterface
{
    private MappingManualFactory $mappingManualFactory;

    private SyncDataExchange $syncDataExchange;

    public function __construct(MappingManualFactory $mappingManualFactory, SyncDataExchange $syncDataExchange)
    {
        $this->mappingManualFactory = $mappingManualFactory;
        $this->syncDataExchange = $syncDataExchange;
    }

    public function getMappingManual(): MappingManualDAO
    {
        return $this->mappingManualFactory->getManual();
    }

    public function getSyncDataExchange(): SyncDataExchangeInterface
    {
        return $this->syncDataExchange;
    }
}
