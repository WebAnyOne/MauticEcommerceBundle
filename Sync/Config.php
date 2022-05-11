<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync;

use Mautic\IntegrationsBundle\Exception\InvalidValueException;
use Mautic\PluginBundle\Entity\Integration;
use MauticPlugin\MauticEcommerceBundle\Integration\EcommerceAbstractIntegration;

class Config
{
    /**
     * @var array[]
     */
    private $fieldDirections = [];

    /**
     * @var array[]
     */
    private $mappedFields = [];

    private EcommerceAbstractIntegration $integration;

    public function __construct(EcommerceAbstractIntegration $integration)
    {
        $this->integration = $integration;
    }

    /**
     * @throws InvalidValueException
     */
    public function getFieldDirection(string $objectName, string $alias): string
    {
        if (isset($this->getMappedFieldsDirections($objectName)[$alias])) {
            return $this->getMappedFieldsDirections($objectName)[$alias];
        }

        throw new InvalidValueException("There is no field direction for '{$objectName}' field '${alias}'.");
    }

    /**
     * Returns mapped fields that the user configured for this integration in the format of [field_alias => mautic_field_alias].
     *
     * @return string[]
     */
    public function getMappedFields(string $objectName): array
    {
        if (isset($this->mappedFields[$objectName])) {
            return $this->mappedFields[$objectName];
        }

        $fieldMappings = $this->getFeatureSettings()['sync']['fieldMappings'][$objectName] ?? [];

        $this->mappedFields[$objectName] = [];
        foreach ($fieldMappings as $field => $fieldMapping) {
            $this->mappedFields[$objectName][$field] = $fieldMapping['mappedField'];
        }

        return $this->mappedFields[$objectName];
    }

    /**
     * @return mixed[]
     */
    public function getFeatureSettings(): array
    {
        return $this->integration->getIntegrationConfiguration()->getFeatureSettings() ?: [];
    }

    /**
     * Returns direction of what field to sync where in the format of [field_alias => direction].
     *
     * @return string[]
     */
    private function getMappedFieldsDirections(string $objectName): array
    {
        if (isset($this->fieldDirections[$objectName])) {
            return $this->fieldDirections[$objectName];
        }

        $fieldMappings = $this->getFeatureSettings()['sync']['fieldMappings'][$objectName] ?? [];

        $this->fieldDirections[$objectName] = [];
        foreach ($fieldMappings as $field => $fieldMapping) {
            $this->fieldDirections[$objectName][$field] = $fieldMapping['syncDirection'];
        }

        return $this->fieldDirections[$objectName];
    }
}
