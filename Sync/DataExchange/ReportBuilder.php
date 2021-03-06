<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\DataExchange;

use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\InputOptionsDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Report\FieldDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Report\ObjectDAO as ReportObjectDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Report\ReportDAO;
use MauticPlugin\MauticEcommerceBundle\Integration\EcommerceAbstractIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Customer;
use MauticPlugin\MauticEcommerceBundle\Model\Product;
use MauticPlugin\MauticEcommerceBundle\Sync\Config;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field\Field;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Field\FieldRepository;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Manual\MappingManualFactory;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ReportBuilder
{
    private FieldRepository $fieldRepository;
    private ValueNormalizer $valueNormalizer;
    private IntegrationsHelper $integrationsHelper;
    private PropertyAccessor $propertyAccessor;

    public function __construct(FieldRepository $fieldRepository, IntegrationsHelper $integrationsHelper)
    {
        $this->fieldRepository = $fieldRepository;

        // Value normalizer transforms value types expected by each side of the sync
        $this->valueNormalizer = new ValueNormalizer();
        $this->integrationsHelper = $integrationsHelper;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function build(int $page, array $requestedObjects, InputOptionsDAO $options): ReportDAO
    {
        /** @var EcommerceAbstractIntegration $integration */
        $integration = $this->integrationsHelper->getIntegration($options->getIntegration());

        $config = $integration->getConfig();
        $client = $integration->getClient();

        $report = new ReportDAO($options->getIntegration());

        foreach ($requestedObjects as $requestedObject) {
            $objectName = $requestedObject->getObject();
            // Fetch a list of changed objects from the integration's API

            switch ($objectName) {
                case MappingManualFactory::CUSTOMER_OBJECT:
                    $modifiedItems = $client->getCustomers($page);
                    break;
                case MappingManualFactory::PRODUCT_OBJECT:
                    $modifiedItems = $client->getProducts($page);
                    break;
                default:
                    throw new \RuntimeException(sprintf('Unsupported objectName "%s"', $objectName));
            }

            // Add the modified items to the report
            $this->addModifiedItems($report, $config, $objectName, $modifiedItems);
        }

        return $report;
    }

    /**
     * @param Customer[]|Product[] $changeList
     */
    private function addModifiedItems(ReportDAO $report, Config $config, string $objectName, array $changeList): void
    {
        // Get the the field list to know what the field types are
        $fields = $this->fieldRepository->getFields($objectName);
        $mappedFields = $config->getMappedFields($objectName);
        $fieldsToSet = array_intersect(array_keys($mappedFields), array_keys($fields));

        foreach ($changeList as $item) {
            $objectDAO = new ReportObjectDAO(
                $objectName,
                // Set the ID from the integration
                $item->id,
                // Set the date/time when the full object was last modified or created
                $item->updatedAt
            );

            foreach ($fieldsToSet as $property) {
                /** @var Field $field */
                $field = $fields[$property];

                // The sync is currently from Integration to Mautic so normalize the values for storage in Mautic
                $value = $this->propertyAccessor->getValue($item, $property);

                $normalizedValue = $this->valueNormalizer->normalizeForMautic(
                    $value,
                    $field->getDataType()
                );

                // If the integration supports field level tracking with timestamps, update FieldDAO::setChangeDateTime as well
                // Note that the field name here is the integration's
                $objectDAO->addField(new FieldDAO($property, $normalizedValue));
            }

            // Add the modified/new item to the report
            $report->addObject($objectDAO);
        }
    }
}
