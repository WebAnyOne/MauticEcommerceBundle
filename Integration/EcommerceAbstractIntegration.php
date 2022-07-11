<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormAuthInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormFeaturesInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormSyncInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\SyncInterface;
use Mautic\IntegrationsBundle\Mapping\MappedFieldInfoInterface;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\MappingManualDAO;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\Internal\Object\Contact;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\SyncDataExchangeInterface;
use MauticPlugin\MauticEcommerceBundle\Sync\Config;
use MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal\Product;
use MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\SyncDataExchange;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field\FieldRepository;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Manual\MappingManualFactory;

abstract class EcommerceAbstractIntegration extends BasicIntegration implements BasicInterface, ConfigFormInterface, ConfigFormAuthInterface, ConfigFormSyncInterface, SyncInterface, EcommerceIntegrationInterface
{
    use DefaultConfigFormTrait;

    protected FieldRepository $fieldRepository;
    protected MappingManualFactory $mappingManualFactory;
    protected SyncDataExchange $syncDataExchange;
    private ?Config $config;

    public function __construct(
        FieldRepository $fieldRepository,
        MappingManualFactory $mappingManualFactory,
        SyncDataExchange $syncDataExchange
    ) {
        $this->fieldRepository = $fieldRepository;
        $this->mappingManualFactory = $mappingManualFactory;
        $this->syncDataExchange = $syncDataExchange;
        $this->config = new Config($this);
    }

    public function getSyncConfigObjects(): array
    {
        return [
            MappingManualFactory::CUSTOMER_OBJECT => 'Customer',
            MappingManualFactory::PRODUCT_OBJECT => 'Products',
        ];
    }

    public function getSyncMappedObjects(): array
    {
        return [
            MappingManualFactory::CUSTOMER_OBJECT => Contact::NAME,
            MappingManualFactory::PRODUCT_OBJECT => Product::NAME,
        ];
    }

    /**
     * @return MappedFieldInfoInterface[]
     */
    public function getRequiredFieldsForMapping(string $objectName): array
    {
        return $this->fieldRepository->getRequiredFieldsForMapping($objectName);
    }

    /**
     * @return MappedFieldInfoInterface[]
     */
    public function getOptionalFieldsForMapping(string $objectName): array
    {
        $this->fieldRepository->getOptionalFieldsForMapping($objectName);
    }

    /**
     * @return MappedFieldInfoInterface[]
     */
    public function getAllFieldsForMapping(string $objectName): array
    {
        // Order fields by required alphabetical then optional alphabetical
        $sorter = function (MappedFieldInfoInterface $field1, MappedFieldInfoInterface $field2) {
            return strnatcasecmp($field1->getLabel(), $field2->getLabel());
        };

        $requiredFields = $this->fieldRepository->getRequiredFieldsForMapping($objectName);
        uasort($requiredFields, $sorter);

        $optionalFields = $this->fieldRepository->getOptionalFieldsForMapping($objectName);
        uasort($optionalFields, $sorter);

        return array_merge(
            $requiredFields,
            $optionalFields
        );
    }

    public function getSupportedFeatures(): array
    {
        return [
            ConfigFormFeaturesInterface::FEATURE_SYNC => 'mautic.integration.feature.sync',
        ];
    }

    public function getMappingManual(): MappingManualDAO
    {
        return $this->mappingManualFactory->getManual(static::NAME);
    }

    public function getSyncDataExchange(): SyncDataExchangeInterface
    {
        return $this->syncDataExchange;
    }

    public function getConfig(): Config
    {
        return $this->config;
    }
}
