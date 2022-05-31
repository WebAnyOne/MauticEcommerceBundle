<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Prestashop\Normalizer;

use MauticPlugin\MauticEcommerceBundle\Integration\PrestashopIntegration;
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
            $data['firstname'],
            $data['lastname'],
            $this->denormalizeDate($data['date_add']),
            $this->denormalizeDate($data['date_upd'])
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = [])
    {
        return $type === Customer::class && $context['integration'] === PrestashopIntegration::NAME;
    }

    protected function denormalizeDate(string $date)
    {
        return $this->denormalizer->denormalize($date, \DateTimeImmutable::class);
    }
}
