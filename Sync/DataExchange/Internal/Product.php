<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal;

use Mautic\IntegrationsBundle\Sync\SyncDataExchange\Internal\Object\ObjectInterface;
use MauticPlugin\MauticEcommerceBundle\Entity\Product as ProductEntity;

final class Product implements ObjectInterface
{
    public const NAME = 'product';
    public const ENTITY = ProductEntity::class;

    public function getName(): string
    {
        return self::NAME;
    }

    public function getEntityName(): string
    {
        return self::ENTITY;
    }

    public static function getFields(): array
    {
        return [
            'name' => 'Name',
            'unitPrice' => 'Unit Price',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
