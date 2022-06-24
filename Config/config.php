<?php

declare(strict_types=1);

use MauticPlugin\MauticEcommerceBundle as Bundle;
use MauticPlugin\MauticEcommerceBundle\EventListener\EmailSubscriber;

return [
    'name' => 'Ecommerce',
    'description' => 'Retrieve data from various ecommerce solutions',
    'version' => '0.0.1',
    'author' => 'elao',
    'services' => [
        'commands' => [
            'mautic_ecommerce.command.transaction_import' => [
                'class' => Bundle\Command\TransactionImportCommand::class,
                'arguments' => [
                    'mautic.integrations.helper',
                    'mautic.integrations.repository.object_mapping',
                    'mautic_ecommerce.repository.transaction',
                    'mautic.lead.repository.lead',
                    'mautic_ecommerce.repository.product',
                    'doctrine',
                ],
                'tag' => 'console.command',
            ],
        ],
        'sync' => [
            'mautic_ecommerce.sync.repository.fields' => [
                'class' => Bundle\Sync\Mapping\Field\FieldRepository::class,
                'arguments' => [
                    'mautic.helper.cache_storage',
                ],
            ],
            'mautic_ecommerce.sync.mapping_manual.factory' => [
                'class' => Bundle\Sync\Mapping\Manual\MappingManualFactory::class,
                'arguments' => [
                    'mautic_ecommerce.sync.repository.fields',
                    'mautic.integrations.helper',
                ],
            ],
            'mautic_ecommerce.sync.data_exchange' => [
                'class' => Bundle\Sync\DataExchange\SyncDataExchange::class,
                'arguments' => [
                    'mautic_ecommerce.sync.data_exchange.report_builder',
                ],
            ],
            'mautic_ecommerce.sync.data_exchange.report_builder' => [
                'class' => Bundle\Sync\DataExchange\ReportBuilder::class,
                'arguments' => [
                    'mautic_ecommerce.sync.repository.fields',
                    'mautic.integrations.helper',
                ],
            ],
            'mautic_ecommerce.sync.data_exchange.product_object_helper' => [
                'class' => Bundle\Sync\DataExchange\Internal\ProductObjectHelper::class,
                'arguments' => [
                    'mautic_ecommerce.repository.product',
                ],
            ],
        ],
        'integrations' => [
            'mautic.integration.prestashop' => [
                'class' => Bundle\Integration\PrestashopIntegration::class,
                'arguments' => [
                    'mautic_ecommerce.sync.repository.fields',
                    'mautic_ecommerce.sync.mapping_manual.factory',
                    'mautic_ecommerce.sync.data_exchange',
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
                    'mautic_ecommerce.sync.repository.fields',
                    'mautic_ecommerce.sync.mapping_manual.factory',
                    'mautic_ecommerce.sync.data_exchange',
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
            'mautic_ecommerce.subscriber.lead' => [
                'class' => Bundle\EventListener\LeadListSubscriber::class,
                'arguments' => [
                    'mautic.lead.provider.typeOperator',
                ],
            ],
            'mautic_ecommerce.subscriber.sync' => [
                'class' => Bundle\EventListener\SyncSubscriber::class,
                'tag' => 'kernel.event_subscriber',
            ],
            'mautic_ecommerce.subscriber.product_object' => [
                'class' => Bundle\EventListener\ProductObjectSubscriber::class,
                'arguments' => [
                    'mautic_ecommerce.sync.data_exchange.product_object_helper',
                ],
                'tag' => 'kernel.event_subscriber',
            ],
            'mautic_ecommerce_subscriber.email' => [
                'class' => EmailSubscriber::class,
                'tag' => 'kernel.event_subscriber',
                'arguments' => ['@mautic_ecommerce.email.parser'],
            ],
        ],
        'repositories' => [
            'mautic_ecommerce.repository.transaction' => [
                'class' => Bundle\Entity\TransactionRepository::class,
                'arguments' => [
                    'doctrine',
                ],
            ],
            'mautic_ecommerce.repository.product' => [
                'class' => Doctrine\ORM\EntityRepository::class,
                'factory' => ['@doctrine.orm.entity_manager', 'getRepository'],
                'arguments' => [
                    Bundle\Entity\Product::class,
                ],
            ],
        ],
        'others' => [
            'mautic_ecommerce.email.parser' => [
                'class' => \MauticPlugin\MauticEcommerceBundle\Email\Parser::class,
                'arguments' => [
                    '@mautic_ecommerce.repository.transaction',
                ],
            ],
        ],
    ],
];
