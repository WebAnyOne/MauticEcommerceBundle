<?php

declare(strict_types=1);

use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\DataExchange\ReportBuilder;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\DataExchange\SyncDataExchange;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\Mapping\Field\FieldRepository;

return [
    'name' => 'WebAnyOne PrestashopBundle ',
    'description' => 'Retrieve data from Prestashop',
    'version' => '0.1',
    'author' => 'elao',
    'routes' => [
        'public' => [
//            'plugin_webanyone_prestashop_test' => array(
//                'path'       => '/test',
//                'controller' => 'WebAnyOneMauticPrestashopBundle:Prestashop:test',
//            ),
        ],
    ],
    'services' => [
        'other' => [
            // Provides access to configured API keys, settings, field mapping, etc
            'webanyone_prestashop.config' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Config::class,
                'arguments' => [
                    'mautic.integrations.helper',
                ],
            ],

            'webanyone_prestashop.command.transaction_import' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Command\TransactionImportCommand::class,
                'arguments' => [
                    'webanyone_prestashop.sync.client_factory',
                    'mautic.integrations.repository.object_mapping',
                    'webanyone_prestashop.repository.transaction',
                    'doctrine',
                ],
                'tags' => [
                    'console.command',
                ],
            ],
            'webanyone_prestashop.repository.transaction' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity\TransactionRepository::class,
                'arguments' => [
                    'doctrine',
                ],
            ],
        ],
        'sync' => [
            'webanyone_prestashop.sync.client_factory' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Api\ClientFactory::class,
                'arguments' => [
                    'mautic.integrations.helper',
                ],
            ],
            // Returns available fields from the integration either from cache or "live" via API
            'webanyone_prestashop.sync.repository.fields' => [
                'class' => FieldRepository::class,
                'arguments' => [
                    'mautic.helper.cache_storage',
                ],
            ],
            // Creates the instructions to the sync engine for which objects and fields to sync and direction of data flow
            'webanyone_prestashop.sync.mapping_manual.factory' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\Mapping\Manual\MappingManualFactory::class,
                'arguments' => [
                    'webanyone_prestashop.sync.repository.fields',
                    'webanyone_prestashop.config',
                ],
            ],
            // Proxies the actions of the sync between Mautic and this integration to the appropriate services
            'webanyone_prestashop.sync.data_exchange' => [
                'class' => SyncDataExchange::class,
                'arguments' => [
                    'webanyone_prestashop.sync.data_exchange.report_builder',
                ],
            ],
            // Builds a report of updated and new objects from the integration to sync with Mautic
            'webanyone_prestashop.sync.data_exchange.report_builder' => [
                'class' => ReportBuilder::class,
                'arguments' => [
                    'webanyone_prestashop.config',
                    'webanyone_prestashop.sync.repository.fields',
                    'webanyone_prestashop.sync.client_factory',
                ],
            ],
        ],
        'integrations' => [
            // Basic definitions with name, display name and icon
            'mautic.integration.prestashop' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration::class,
                'tags' => [
                    'mautic.integration',
                    'mautic.basic_integration',
                ],
            ],
            // Provides the form types to use for the configuration UI
            'webanyone_prestashop.integration.configuration' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support\ConfigSupport::class,
                'arguments' => [
                    'webanyone_prestashop.sync.repository.fields',
                ],
                'tags' => [
                    'mautic.config_integration',
                ],
            ],
            'webanyone_prestashop.integration.sync' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support\SyncSupport::class,
                'arguments' => [
                    'webanyone_prestashop.sync.mapping_manual.factory',
                    'webanyone_prestashop.sync.data_exchange',
                ],
                'tags' => [
                    'mautic.sync_integration',
                ],
            ],
        ],
        'events' => [
            'webanyone.prestashop.subscriber.lead' => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\EventListener\LeadListSubscriber::class,
                'arguments' => [
                    'mautic.lead.provider.typeOperator',
                ],
            ],
        ],
    ],
];
