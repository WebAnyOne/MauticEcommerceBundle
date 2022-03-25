<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\DataExchange;

use Mautic\IntegrationsBundle\Sync\DAO\Sync\InputOptionsDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Report\FieldDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Report\ObjectDAO as ReportObjectDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Report\ReportDAO;
use Mautic\IntegrationsBundle\Sync\DAO\Sync\Request\ObjectDAO as RequestObjectDAO;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\ClientFactory;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Config;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\Mapping\Field\Field;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\Mapping\Field\FieldRepository;

class ReportBuilder
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var FieldRepository
     */
    private $fieldRepository;

    /**
     * @var ValueNormalizer
     */
    private $valueNormalizer;

    /**
     * @var ReportDAO
     */
    private $report;

    private ClientFactory $clientFactory;

    public function __construct(Config $config, FieldRepository $fieldRepository, ClientFactory  $clientFactory)
    {
        $this->config          = $config;
        $this->fieldRepository = $fieldRepository;

        // Value normalizer transforms value types expected by each side of the sync
        $this->valueNormalizer = new ValueNormalizer();
        $this->clientFactory = $clientFactory;
    }

    public function build(int $page, array $requestedObjects, InputOptionsDAO $options): ReportDAO
    {
        dump('build', func_get_args());
        $client = $this->clientFactory->getClient();

        // Set the options this integration supports (see InputOptionsDAO for others)
        $startDateTime = $options->getStartDateTime();
        $endDateTime   = $options->getEndDateTime();

        $this->report = new ReportDAO(PrestashopIntegration::NAME);

        foreach ($requestedObjects as $requestedObject) {
            dump($requestedObject);
            $objectName = $requestedObject->getObject();
            // Fetch a list of changed objects from the integration's API

dump($objectName);
            $customers = $client->getCustomers($page);
            // todo retrieve customer from the api
dump($customers);
            $modifiedItems = [];

            // Add the modified items to the report
            $this->addModifiedItems($objectName, $modifiedItems);
        }

        return $this->report;
    }

    private function addModifiedItems(string $objectName, array $changeList): void
    {
        // Get the the field list to know what the field types are
        $fields       = $this->fieldRepository->getFields($objectName);
        $mappedFields = $this->config->getMappedFields($objectName);

        foreach ($changeList as $item) {
            $objectDAO = new ReportObjectDAO(
                $objectName,
                // Set the ID from the integration
                $item['id'],
                // Set the date/time when the full object was last modified or created
                new \DateTime(!empty($item['last_modified_timestamp']) ? $item['last_modified_timestamp'] : $item['created_timestamp'])
            );

            foreach ($item['fields'] as $fieldAlias => $fieldValue) {
                if (!isset($fields[$fieldAlias]) || !isset($mappedFields[$fieldAlias])) {
                    // Field is not recognized or it's not mapped so ignore
                    continue;
                }

                /** @var Field $field */
                $field = $fields[$fieldAlias];

                // The sync is currently from Integration to Mautic so normalize the values for storage in Mautic
                $normalizedValue = $this->valueNormalizer->normalizeForMautic(
                    $fieldValue,
                    $field->getDataType()
                );

                // If the integration supports field level tracking with timestamps, update FieldDAO::setChangeDateTime as well
                // Note that the field name here is the integration's
                $objectDAO->addField(new FieldDAO($fieldAlias, $normalizedValue));
            }

            // Add the modified/new item to the report
            $this->report->addObject($objectDAO);
        }
    }
}
