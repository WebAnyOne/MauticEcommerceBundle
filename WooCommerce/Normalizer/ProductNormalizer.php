<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Integration\WooCommerceIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Product;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class ProductNormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = []): Product
    {
        return new Product(
            (int) $data['id'],
            $data['name'],
            (int) (((float) $data['price']) * 100),
            $this->denormalizeDate($data['date_created']),
            $this->denormalizeDate($data['date_modified'])
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $type === Product::class && $context['integration'] === WooCommerceIntegration::NAME;
    }

    protected function denormalizeDate(string $date): \DateTimeImmutable
    {
        return $this->denormalizer->denormalize($date, \DateTimeImmutable::class);
    }
}
