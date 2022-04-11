<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Api;

use GuzzleHttp\Psr7\Utils;

class Client implements ClientInterface
{
    private \GuzzleHttp\Client $httpClient;

    public function __construct(string $url, string $token)
    {
        $uri = Utils::uriFor(rtrim($url, '/') . '/');
        $uri = $uri->withUserInfo($token);

        $this->httpClient = new \GuzzleHttp\Client(['base_uri' => $uri]);
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
                    'output_format' => 'JSON'
                ],
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['customers'] ?? [];
    }

    public function getOrders(int $page, int $limit = 100): array
    {
        $offset = ($page - 1) * $limit;

        $response = $this->httpClient->get(
            'orders',
            [
                'query' => [
                    'display' => 'full',
                    'limit' => $offset . ',' . $limit,
                    'output_format' => 'JSON'
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true)['orders'] ?? [];
    }
}
