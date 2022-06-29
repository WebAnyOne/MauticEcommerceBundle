<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\WooCommerce\Api;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Utils;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use MauticPlugin\MauticEcommerceBundle\Api\ClientInterface;
use MauticPlugin\MauticEcommerceBundle\Integration\WooCommerceIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Customer;
use MauticPlugin\MauticEcommerceBundle\Model\Order;
use MauticPlugin\MauticEcommerceBundle\Model\Product;
use MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer\CustomerNormalizer;
use MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer\OrderNormalizer;
use MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer\OrderProductNormalizer;
use MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer\ProductNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Serializer;

class Client implements ClientInterface
{
    private \GuzzleHttp\Client $httpClient;
    private Serializer $serializer;

    public function __construct(string $url, string $consumerKey, string $consumerSecret)
    {
        $uri = Utils::uriFor(rtrim($url, '/') . '/');

        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
        ]);

        $stack->push($middleware);

        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $uri,
            'handler' => $stack,
            'auth' => 'oauth',
        ]);

        $this->serializer = new Serializer(
            [
                new ArrayDenormalizer(),
                new DateTimeNormalizer(),
                new CustomerNormalizer(),
                new OrderNormalizer(),
                new ProductNormalizer(),
                new OrderProductNormalizer(),
            ],
            [new JsonEncoder()]
        );
    }

    public function getCustomers(int $page, int $limit = 100): array
    {
        $response = $this->httpClient->get('customers', ['query' => [
            'page' => $page,
            'per_page' => $limit,
            'orderby' => 'id',
        ]]);

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            Customer::class . '[]',
            JsonEncoder::FORMAT,
            ['integration' => WooCommerceIntegration::NAME]
        );
    }

    public function getOrders(int $page, int $limit = 100): array
    {
        $response = $this->httpClient->get('orders', ['query' => [
            'page' => $page,
            'per_page' => $limit,
            'orderby' => 'id',
        ]]);

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            Order::class . '[]',
            JsonEncoder::FORMAT,
            ['integration' => WooCommerceIntegration::NAME]
        );
    }

    public function getProducts(int $page, int $limit = 100): array
    {
        $response = $this->httpClient->get('products', ['query' => [
            'page' => $page,
            'per_page' => $limit,
            'orderby' => 'id',
        ]]);

        return $this->serializer->deserialize(
            $response->getBody()->getContents(),
            Product::class . '[]',
            JsonEncoder::FORMAT,
            ['integration' => WooCommerceIntegration::NAME]
        );
    }
}
