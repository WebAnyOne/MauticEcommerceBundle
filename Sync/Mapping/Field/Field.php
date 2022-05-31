<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field;

use Mautic\IntegrationsBundle\Sync\DAO\Mapping\ObjectMappingDAO;

class Field
{
    private $name;
    private $label;
    private $dataType;
    private $isRequired;
    private $isWritable;

    public function __construct(array $field = [])
    {
        $this->name = $field['name'] ?? '';
        $this->label = $field['label'] ?? '';
        $this->dataType = $field['data_type'] ?? 'text';
        $this->isRequired = (bool) ($field['required'] ?? false);
        $this->isWritable = (bool) ($field['writable'] ?? true);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function isWritable(): bool
    {
        return $this->isWritable;
    }

    public function getSupportedSyncDirection(): string
    {
        return $this->isWritable ? ObjectMappingDAO::SYNC_BIDIRECTIONALLY : ObjectMappingDAO::SYNC_TO_MAUTIC;
    }
}
