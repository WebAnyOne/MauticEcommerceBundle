<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Model\OrderProduct;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class OrderProductNormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new OrderProduct(
            (int) $data['product_id'],
            (int) $data['quantity'],
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === OrderProduct::class;
    }
}
