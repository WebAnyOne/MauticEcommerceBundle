<?php

return [
    'name' => 'WebAnyOne PrestashopBundle ',
    'description' => 'Retrieve data from Prestashop',
    'version' => '0.1',
    'author' => 'elao',
    'routes' => [],
    'services' => [
        'other'        => [
            // Provides access to configured API keys, settings, field mapping, etc
            'webanyone_prestashop.config'            => [
                'class'     => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Config::class,
                'arguments' => [
                    'mautic.integrations.helper',
                ],
            ],
        ],
        'integrations' => [
            // Basic definitions with name, display name and icon
            'mautic.integration.prestashop'               => [
                'class' => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration::class,
                'tags'  => [
                    'mautic.integration',
                    'mautic.basic_integration',
                ],
            ],
            // Provides the form types to use for the configuration UI
            'webanyone_prestashop.integration.configuration' => [
                'class'     => \MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support\ConfigSupport::class,
                'tags'      => [
                    'mautic.config_integration',
                ],
            ],
        ],
    ],
];
