<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Prestashop\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Integration\PrestashopIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Order;
use MauticPlugin\MauticEcommerceBundle\Model\OrderProduct;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class OrderNormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = []): Order
    {
        return new Order(
            (int) $data['id'],
            (int) $data['id_customer'],
            $this->denormalizer->denormalize($data['date_add'], \DateTimeImmutable::class),
            (int) ((float) $data['total_paid_tax_excl'] * 100),
            (int) ((float) $data['total_paid_tax_incl'] * 100),
            \count($data['associations']['order_rows']),
            $this->denormalizer->denormalize($data['associations']['order_rows'], OrderProduct::class . '[]'),
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $type === Order::class && $context['integration'] === PrestashopIntegration::NAME;
    }
}
