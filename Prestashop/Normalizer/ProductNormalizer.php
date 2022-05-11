<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Prestashop\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Integration\PrestashopIntegration;
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
            $this->denormalizeDate($data['date_add']),
            $this->denormalizeDate($data['date_upd'])
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $type === Product::class && $context['integration'] === PrestashopIntegration::NAME;
    }

    protected function denormalizeDate(string $date)
    {
        return $this->denormalizer->denormalize($date, \DateTimeImmutable::class);
    }
}
