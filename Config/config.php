<?php

declare(strict_types=1);

use MauticPlugin\MauticEcommerceBundle as Bundle;

return [
    'name' => 'Ecommerce',
    'description' => 'Retrieve data from various ecommerce solutions',
    'version' => '1.0.0',
    'author' => 'elao',
    'services' => [
        'other' => [
            'webanyone_prestashop.repository.transaction' => [
                'class' => Bundle\Entity\TransactionRepository::class,
                'arguments' => [
                    'doctrine',
                ],
            ],
        ],
        'commands' => [
            'webanyone_prestashop.command.transaction_import' => [
                'class' => Bundle\Command\TransactionImportCommand::class,
                'arguments' => [
                    'mautic.integrations.helper',
                    'mautic.integrations.repository.object_mapping',
                    'webanyone_prestashop.repository.transaction',
                    'doctrine',
                ],
                'tag' => 'console.command',
            ],
        ],
        'sync' => [
            'webanyone_prestashop.sync.repository.fields' => [
                'class' => Bundle\Sync\Mapping\Field\FieldRepository::class,
                'arguments' => [
                    'mautic.helper.cache_storage',
                ],
            ],
            'webanyone_prestashop.sync.mapping_manual.factory' => [
                'class' => Bundle\Sync\Mapping\Manual\MappingManualFactory::class,
                'arguments' => [
                    'webanyone_prestashop.sync.repository.fields',
                    'mautic.integrations.helper',
                ],
            ],
            'webanyone_prestashop.sync.data_exchange' => [
                'class' => Bundle\Sync\DataExchange\SyncDataExchange::class,
                'arguments' => [
                    'webanyone_prestashop.sync.data_exchange.report_builder',
                ],
            ],
            'webanyone_prestashop.sync.data_exchange.report_builder' => [
                'class' => Bundle\Sync\DataExchange\ReportBuilder::class,
                'arguments' => [
                    'webanyone_prestashop.sync.repository.fields',
                    'mautic.integrations.helper',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.prestashop' => [
                'class' => Bundle\Integration\PrestashopIntegration::class,
                'arguments' => [
                    'webanyone_prestashop.sync.repository.fields',
                    'webanyone_prestashop.sync.mapping_manual.factory',
                    'webanyone_prestashop.sync.data_exchange',
                ],
                'tags' => [
                    'mautic.integration',
                    'mautic.basic_integration',
                    'mautic.config_integration',
                    'mautic.sync_integration',
                ],
            ],
            'mautic.integration.woocommerce' => [
                'class' => Bundle\Integration\WooCommerceIntegration::class,
                'arguments' => [
                    'webanyone_prestashop.sync.repository.fields',
                    'webanyone_prestashop.sync.mapping_manual.factory',
                    'webanyone_prestashop.sync.data_exchange',
                ],
                'tags' => [
                    'mautic.integration',
                    'mautic.basic_integration',
                    'mautic.config_integration',
                    'mautic.sync_integration',
                ],
            ],
        ],
        'events' => [
            'webanyone_prestashop.subscriber.lead' => [
                'class' => Bundle\EventListener\LeadListSubscriber::class,
                'arguments' => [
                    'mautic.lead.provider.typeOperator',
                ],
            ],
        ],
    ],
];
