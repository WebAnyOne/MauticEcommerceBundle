<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\WooCommerce\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Integration\WooCommerceIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Customer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

class CustomerNormalizer implements ContextAwareDenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $type, string $format = null, array $context = []): Customer
    {
        return new Customer(
            (string) $data['id'],
            $data['email'],
            $data['first_name'],
            $data['last_name'],
            $this->denormalizeDate($data['date_created_gmt']),
            $this->denormalizeDate($data['date_modified_gmt'])
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $type === Customer::class && $context['integration'] === WooCommerceIntegration::NAME;
    }

    protected function denormalizeDate(string $date): \DateTimeImmutable
    {
        return $this->denormalizer->denormalize(
            $date,
            \DateTimeImmutable::class,
        );
    }
}
