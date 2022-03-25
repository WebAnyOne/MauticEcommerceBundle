<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle;

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

    public function getCustomers(int $page, int $limit = 100): array
    {
        $offset = ($page - 1) * $limit;

            $response = $this->httpClient->get(
                'customers?sort=[date_add_DESC]&date=1&display=full&lmit=' . $offset . ',' . $limit
            );

            dump(new \SimpleXMLElement($response->getBody()->getContents()));
//         dd($response->getBody()->getContents());

         return [];
    }
}
