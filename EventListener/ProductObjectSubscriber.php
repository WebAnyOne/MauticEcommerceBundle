<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\EventListener;

use Mautic\IntegrationsBundle\Event\InternalObjectCreateEvent;
use Mautic\IntegrationsBundle\Event\InternalObjectEvent;
use Mautic\IntegrationsBundle\Event\InternalObjectUpdateEvent;
use Mautic\IntegrationsBundle\IntegrationEvents;
use MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal\Product;
use MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal\ProductObjectHelper;

class ProductObjectSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    private ProductObjectHelper $productObjectHelper;

    public function __construct(ProductObjectHelper $productObjectHelper)
    {
        $this->productObjectHelper = $productObjectHelper;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            IntegrationEvents::INTEGRATION_COLLECT_INTERNAL_OBJECTS => ['collectInternalObjects', 0],
            IntegrationEvents::INTEGRATION_UPDATE_INTERNAL_OBJECTS => ['updateProducts', 0],
            IntegrationEvents::INTEGRATION_CREATE_INTERNAL_OBJECTS => ['createProducts', 0],
        ];
    }

    public function collectInternalObjects(InternalObjectEvent $event): void
    {
        $event->addObject(new Product());
    }

    public function updateProducts(InternalObjectUpdateEvent $event): void
    {
        if (Product::NAME !== $event->getObject()->getName()) {
            return;
        }

        $event->setUpdatedObjectMappings(
            $this->productObjectHelper->update(
                $event->getIdentifiedObjectIds(),
                $event->getUpdateObjects()
            )
        );
        $event->stopPropagation();
    }

    public function createProducts(InternalObjectCreateEvent $event): void
    {
        if (Product::NAME !== $event->getObject()->getName()) {
            return;
        }

        $event->setObjectMappings($this->productObjectHelper->create($event->getCreateObjects()));
        $event->stopPropagation();
    }
}
