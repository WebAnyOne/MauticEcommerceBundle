<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\Support;

use GuzzleHttp\ClientInterface;
use Mautic\IntegrationsBundle\Auth\Provider\AuthConfigInterface;
use Mautic\IntegrationsBundle\Auth\Provider\AuthCredentialsInterface;
use Mautic\IntegrationsBundle\Auth\Provider\AuthProviderInterface;

class AuthSupport implements AuthProviderInterface
{

    public function getAuthType(): string
    {
        return 'url';
    }

    public function getClient(
        AuthCredentialsInterface $credentials,
        ?AuthConfigInterface $config = null
    ): ClientInterface {
        var_dump($credentials, $config);die();
        // TODO: Implement getClient() method.
    }
}