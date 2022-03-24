<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support;

use Mautic\IntegrationsBundle\Integration\DefaultConfigFormTrait;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormAuthInterface;
use Mautic\IntegrationsBundle\Integration\Interfaces\ConfigFormInterface;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Form\Type\ConfigFormType;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;

class ConfigSupport extends PrestashopIntegration implements ConfigFormInterface, ConfigFormAuthInterface
{
    use DefaultConfigFormTrait;

    public function getAuthConfigFormName(): string
    {
        return ConfigFormType::class;
    }
}
