<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field;

use Mautic\CoreBundle\Helper\CacheStorageHelper;
use Mautic\IntegrationsBundle\Sync\DAO\Value\NormalizedValueDAO;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Manual\MappingManualFactory;

class FieldRepository
{
    private static $fields = [
        MappingManualFactory::CUSTOMER_OBJECT => [
            [
                'name' => 'id',
                'label' => 'ID',
                'data_type' => NormalizedValueDAO::STRING_TYPE,
                'required' => true,
                'writable' => false,
            ],
            [
                'name' => 'lastName',
                'label' => 'Last Name',
                'data_type' => NormalizedValueDAO::STRING_TYPE,
                'required' => true,
                'writable' => false,
            ],
            [
                'name' => 'firstName',
                'label' => 'First Name',
                'data_type' => NormalizedValueDAO::STRING_TYPE,
                'required' => true,
                'writable' => false,
            ],
            [
                'name' => 'email',
                'label' => 'Email',
                'data_type' => NormalizedValueDAO::STRING_TYPE,
                'required' => true,
                'writable' => false,
            ],
            [
                'name' => 'createdAt',
                'label' => 'Date add',
                'data_type' => NormalizedValueDAO::DATETIME_TYPE,
                'required' => true,
                'writable' => false,
            ],
            [
                'name' => 'updatedAt',
                'label' => 'Date update',
                'data_type' => NormalizedValueDAO::DATETIME_TYPE,
                'required' => true,
                'writable' => false,
            ],
        ],
    ];
    /**
     * @var CacheStorageHelper
     */
    private $cacheProvider;

    /**
     * @var array
     */
    private $apiFields = [];

    public function __construct(CacheStorageHelper $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * Used by the sync engine so that it does not have to fetch the fields live with each object sync.
     *
     * @return Field[]
     */
    public function getFields(string $objectName): array
    {
        $cacheKey = $this->getCacheKey($objectName);
        $fields = $this->cacheProvider->get($cacheKey);

        if (!$fields) {
            // Fields are empty or not found so refresh from the API
            $fields = $this->getFieldsFromApi($objectName);
        }

        return $this->hydrateFieldObjects($fields);
    }

    /**
     * @return MappedFieldInfo[]
     */
    public function getRequiredFieldsForMapping(string $objectName): array
    {
        $fields = $this->getFieldsFromApi($objectName);
        $fieldObjects = $this->hydrateFieldObjects($fields);

        $requiredFields = [];
        foreach ($fieldObjects as $field) {
            if (!$field->isRequired()) {
                continue;
            }

            // Fields must have the name as the key
            $requiredFields[$field->getName()] = new MappedFieldInfo($field);
        }

        return $requiredFields;
    }

    /**
     * @return MappedFieldInfo[]
     */
    public function getOptionalFieldsForMapping(string $objectName): array
    {
        $fields = $this->getFieldsFromApi($objectName);
        $fieldObjects = $this->hydrateFieldObjects($fields);

        $optionalFields = [];
        foreach ($fieldObjects as $field) {
            if ($field->isRequired()) {
                continue;
            }

            // Fields must have the name as the key
            $optionalFields[$field->getName()] = new MappedFieldInfo($field);
        }

        return $optionalFields;
    }

    /**
     * Used by the config form to fetch the fields fresh from the API.
     */
    private function getFieldsFromApi(string $objectName): array
    {
        if (isset($this->apiFields[$objectName])) {
            return $this->apiFields[$objectName];
        }

        // todo retrieve a list of fields for a given objectName
        // [{name, label, data_type, required, writable}]

        $fields = self::$fields[$objectName];

        // Refresh the cache with the fields just fetched
        $cacheKey = $this->getCacheKey($objectName);
        $this->cacheProvider->set($cacheKey, $fields);

        $this->apiFields[$objectName] = $fields;

        return $this->apiFields[$objectName];
    }

    private function getCacheKey(string $objectName): string
    {
        return sprintf('helloworld.fields.%s', $objectName);
    }

    /**
     * @return Field[]
     */
    private function hydrateFieldObjects(array $fields): array
    {
        $fieldObjects = [];
        foreach ($fields as $field) {
            $fieldObjects[$field['name']] = new Field($field);
        }

        return $fieldObjects;
    }
}
