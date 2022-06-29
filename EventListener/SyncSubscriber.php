<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\EventListener;

use Mautic\IntegrationsBundle\Event\MauticSyncFieldsLoadEvent;
use Mautic\IntegrationsBundle\IntegrationEvents;
use MauticPlugin\MauticEcommerceBundle\Sync\DataExchange\Internal\Product;

class SyncSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            IntegrationEvents::INTEGRATION_MAUTIC_SYNC_FIELDS_LOAD => 'onSyncFieldsLoad',
        ];
    }

    public function onSyncFieldsLoad(MauticSyncFieldsLoadEvent $event): void
    {
        if ($event->getObjectName() === Product::NAME) {
            foreach (Product::getFields() as $field => $label) {
                $event->addField($field, $label);
            }
        }
    }
}
