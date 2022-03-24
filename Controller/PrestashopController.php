<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Controller;

use Mautic\CoreBundle\Controller\CommonController;
use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;
use Symfony\Component\HttpFoundation\Response;

class PrestashopController extends CommonController
{
    public function testAction()
    {
        /** @var IntegrationsHelper $integrationsHelper */
        $integrationsHelper = $this->get('mautic.integrations.helper');

        /** @var PrestashopIntegration $integration */
        $integration = $integrationsHelper->getIntegration('Prestashop');

        var_dump($integration->makeRequest('/customers'));

        return new Response('<body></body>');
    }
}
