<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Integration\WooCommerceIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Order;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class OrderNormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = []): Order
    {
        $total = (int) (((float) $data['total']) * 100);
        $taxes = (int) (((float) $data['total_tax']) * 100);

        return new Order(
            (int) $data['id'],
            (int) $data['customer_id'],
            $this->denormalizer->denormalize($data['date_created'], \DateTimeImmutable::class),
            $total - $taxes,
            $total,
            \count($data['line_items'])
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === Order::class && $context['integration'] === WooCommerceIntegration::NAME;
    }
}
