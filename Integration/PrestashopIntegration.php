<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration;

use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;

class PrestashopIntegration extends BasicIntegration implements BasicInterface
{
    public const NAME = 'Prestashop';

    public function getIcon(): string
    {
        return 'plugins/WebAnyOneMauticPrestashopBundle/Assets/img/grapesjsbuilder.png';
    }

    public function getDisplayName(): string
    {
        return 'WebAnyOne Prestashop';
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
