<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Utils;
use Mautic\IntegrationsBundle\Integration\BasicIntegration;
use Mautic\IntegrationsBundle\Integration\Interfaces\BasicInterface;

class PrestashopIntegration extends BasicIntegration implements BasicInterface
{
    public const NAME = 'Prestashop';

    public function getIcon(): string
    {
        return 'plugins/WebAnyOneMauticPrestashopBundle/Assets/img/grapesjsbuilder.png';
    }

    public function makeRequest(string $url)
    {
        $config = $this->getIntegrationConfiguration()->getApiKeys();

        $uri = Utils::uriFor(rtrim($config['url'], '/') . '/');
        $uri = $uri->withUserInfo($config['token']);

        $client = new Client(['base_uri' => $uri]);

        return new \SimpleXMLElement($client->get(ltrim($url, '/'))->getBody()->getContents());
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
