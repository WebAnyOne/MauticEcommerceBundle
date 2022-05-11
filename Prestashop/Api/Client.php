<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Prestashop\Api;

use GuzzleHttp\Psr7\Utils;
use MauticPlugin\MauticEcommerceBundle\Api\ClientInterface;
use MauticPlugin\MauticEcommerceBundle\Integration\PrestashopIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Customer;
use MauticPlugin\MauticEcommerceBundle\Model\Order;
use MauticPlugin\MauticEcommerceBundle\Prestashop\Normalizer\CustomerNormalizer;
use MauticPlugin\MauticEcommerceBundle\Prestashop\Normalizer\OrderNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
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
        $this->serializer = new Serializer(
            [new ArrayDenormalizer(), new DateTimeNormalizer(), new CustomerNormalizer(), new OrderNormalizer()],
            [new JsonEncoder()]
        );
    }

    public function getCustomers(int $page, int $limit = 100): array
    {
        $offset = ($page - 1) * $limit;

        $response = $this->httpClient->get(
            'customers',
            [
                'query' => [
                    'display' => 'full',
                    'limit' => $offset . ',' . $limit,
                    'output_format' => 'JSON',
                ],
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->denormalize(
            $data['customers'] ?? [],
            Customer::class . '[]',
            JsonEncoder::FORMAT,
            ['integration' => PrestashopIntegration::NAME]
        );
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
                    'output_format' => 'JSON',
                ],
            ]
        );

        $data = json_decode($response->getBody()->getContents(), true);

        return $this->serializer->denormalize(
            $data['orders'] ?? [],
            Order::class . '[]',
            JsonEncoder::FORMAT,
            ['integration' => PrestashopIntegration::NAME]
        );
    }
}
