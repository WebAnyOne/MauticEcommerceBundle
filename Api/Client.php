<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Api;

use GuzzleHttp\Psr7\Utils;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;

class Client implements ClientInterface
{
    private \GuzzleHttp\Client $httpClient;

    private Serializer $serializer;

    public function __construct(string $url, string $token)
    {
        $uri = Utils::uriFor(rtrim($url, '/') . '/');
        $uri = $uri->withUserInfo($token);

        $this->httpClient = new \GuzzleHttp\Client(['base_uri' => $uri]);

        $this->decoder = new XmlEncoder();
    }

    public function getCustomers(int $page, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        $response = $this->httpClient->get(
            'customers',
            [
                'query' => [
                    'display' => 'full',
                    'limit' => $offset . ',' . $limit,
                ],
            ]
        );

        $data = $this->decoder->decode($response->getBody()->getContents(), XmlEncoder::FORMAT);

        return $data['customers']['customer'] ?? [];
    }
}
