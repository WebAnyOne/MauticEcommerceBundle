<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration;

use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;

class Config
{
    private IntegrationsHelper $integrationsHelper;

    public function __construct(IntegrationsHelper $integrationsHelper)
    {
        $this->integrationsHelper = $integrationsHelper;
    }
}
