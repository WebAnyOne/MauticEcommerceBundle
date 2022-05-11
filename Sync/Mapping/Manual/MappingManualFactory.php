<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Manual;

use Mautic\IntegrationsBundle\Exception\InvalidValueException;
use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\MappingManualDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Mapping\ObjectMappingDAO;
use Mautic\IntegrationsBundle\Sync\SyncDataExchange\Internal\Object\Contact;
use MauticPlugin\MauticEcommerceBundle\Integration\EcommerceAbstractIntegration;
use MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal\Product;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field\Field;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field\FieldRepository;

class MappingManualFactory
{
    public const CUSTOMER_OBJECT = 'customer';
    public const PRODUCT_OBJECT = 'product';

    /**
     * @var FieldRepository
     */
    private $fieldRepository;

    /**
     * @var MappingManualDAO
     */
    private $manual;

    private IntegrationsHelper $integrationsHelper;

    public function __construct(FieldRepository $fieldRepository, IntegrationsHelper $integrationsHelper)
    {
        $this->fieldRepository = $fieldRepository;
        $this->integrationsHelper = $integrationsHelper;
    }

    public function getManual(string $integrationName): MappingManualDAO
    {
        if ($this->manual) {
            return $this->manual;
        }

        $this->manual = new MappingManualDAO($integrationName);

        $this->configureObjectMapping($integrationName, self::CUSTOMER_OBJECT);
        $this->configureObjectMapping($integrationName, self::PRODUCT_OBJECT);

        return $this->manual;
    }

    private function configureObjectMapping(string $integrationName, string $objectName): void
    {
        /** @var EcommerceAbstractIntegration $integration */
        $integration = $this->integrationsHelper->getIntegration($integrationName);

        // Get a list of available fields from the integration
        $fields = $this->fieldRepository->getFields($objectName);

        // Get a list of fields mapped by the user
        $config = $integration->getConfig();
        $mappedFields = $config->getMappedFields($objectName);

        // Generate an object mapping DAO for the given object. The object must be mapped to a supported Mautic object (i.e. contact or company)
        $objectMappingDAO = new ObjectMappingDAO($this->getMauticObjectName($objectName), $objectName);

        foreach ($mappedFields as $fieldAlias => $mauticFieldAlias) {
            if (!isset($fields[$fieldAlias])) {
                // The mapped field is no longer available
                continue;
            }

            /** @var Field $field */
            $field = $fields[$fieldAlias];

            // Configure how fields should be handled by the sync engine as determined by the user's configuration.
            $objectMappingDAO->addFieldMapping(
                $mauticFieldAlias,
                $fieldAlias,
                $config->getFieldDirection($objectName, $fieldAlias),
                $field->isRequired()
            );

            $this->manual->addObjectMapping($objectMappingDAO);
        }
    }

    /**
     * @throws InvalidValueException
     */
    private function getMauticObjectName(string $objectName): string
    {
        switch ($objectName) {
            case self::CUSTOMER_OBJECT:
                return Contact::NAME;
            case self::PRODUCT_OBJECT:
                return Product::NAME;
        }

        throw new InvalidValueException("$objectName could not be mapped to a Mautic object");
    }
}
