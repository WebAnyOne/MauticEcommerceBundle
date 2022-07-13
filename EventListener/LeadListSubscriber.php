<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\EventListener;

use Mautic\LeadBundle\Event\LeadListFiltersChoicesEvent;
use Mautic\LeadBundle\Event\SegmentDictionaryGenerationEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\LeadBundle\Provider\TypeOperatorProviderInterface;
use Mautic\LeadBundle\Segment\OperatorOptions;
use Mautic\LeadBundle\Segment\Query\Filter\ForeignFuncFilterQueryBuilder;
use Mautic\LeadBundle\Segment\Query\Filter\ForeignValueFilterQueryBuilder;
use MauticPlugin\MauticEcommerceBundle\Segment\Query\Filter\PurchaseProductFilterQueryBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadListSubscriber implements EventSubscriberInterface
{
    private TypeOperatorProviderInterface $typeOperatorProvider;

    public function __construct(TypeOperatorProviderInterface $typeOperatorProvider)
    {
        $this->typeOperatorProvider = $typeOperatorProvider;
    }

    public static function getSubscribedEvents()
    {
        return [
            LeadEvents::LIST_FILTERS_CHOICES_ON_GENERATE => 'onGenerateSegmentFiltersAddTransactionFields',
            LeadEvents::SEGMENT_DICTIONARY_ON_GENERATE => 'onGenerateSegmentDictionary',
        ];
    }

    public function onGenerateSegmentFiltersAddTransactionFields(LeadListFiltersChoicesEvent $event): void
    {
        $event->addChoice('lead', 'lead_order_date', [
            'label' => 'Date de dernière commande',
            'object' => 'lead',
            'properties' => ['type' => 'date'],
            'operators' => $this->typeOperatorProvider->getOperatorsIncluding([
                OperatorOptions::EQUAL_TO,
                OperatorOptions::NOT_EQUAL_TO,
                OperatorOptions::GREATER_THAN,
                OperatorOptions::LESS_THAN,
                OperatorOptions::GREATER_THAN_OR_EQUAL,
                OperatorOptions::LESS_THAN_OR_EQUAL,
            ]),
        ]);

        $event->addChoice('lead', 'lead_transaction_count', [
            'label' => 'Nombre de commande',
            'object' => 'lead',
            'properties' => ['type' => 'number'],
            'operators' => $this->typeOperatorProvider->getOperatorsIncluding([
                OperatorOptions::EQUAL_TO,
                OperatorOptions::NOT_EQUAL_TO,
                OperatorOptions::GREATER_THAN,
                OperatorOptions::LESS_THAN,
                OperatorOptions::GREATER_THAN_OR_EQUAL,
                OperatorOptions::LESS_THAN_OR_EQUAL,
            ]),
        ]);

        $event->addChoice('lead', 'lead_transaction_sum_price_with_taxes', [
            'label' => 'CA Cumulé',
            'object' => 'lead',
            'properties' => ['type' => 'money'],
            'operators' => $this->typeOperatorProvider->getOperatorsIncluding([
                OperatorOptions::EQUAL_TO,
                OperatorOptions::NOT_EQUAL_TO,
                OperatorOptions::GREATER_THAN,
                OperatorOptions::LESS_THAN,
                OperatorOptions::GREATER_THAN_OR_EQUAL,
                OperatorOptions::LESS_THAN_OR_EQUAL,
            ]),
        ]);

        $event->addChoice('lead', 'lead_transaction_purchase_product', [
            'label' => 'Produit acheté',
            'object' => 'lead',
            'properties' => [
                'type' => 'lookup_id',
                'data-action' => 'plugin:Ecommerce:products',
            ],
            'operators' => $this->typeOperatorProvider->getOperatorsIncluding([
                OperatorOptions::EQUAL_TO,
                OperatorOptions::NOT_EQUAL_TO,
            ]),
        ]);
    }

    public function onGenerateSegmentDictionary(SegmentDictionaryGenerationEvent $event): void
    {
        $event->addTranslation('lead_order_date', [
            'type' => ForeignValueFilterQueryBuilder::getServiceId(),
            'foreign_table' => 'ecommerce_transaction',
            'field' => 'date',
        ]);

        $event->addTranslation('lead_transaction_count', [
            'type' => ForeignFuncFilterQueryBuilder::getServiceId(),
            'foreign_table' => 'ecommerce_transaction',
            'foreign_table_field' => 'lead_id',
            'table' => 'leads',
            'table_field' => 'id',
            'func' => 'count',
            'field' => 'id',
        ]);

        $event->addTranslation('lead_transaction_sum_price_with_taxes', [
            'type' => ForeignFuncFilterQueryBuilder::getServiceId(),
            'foreign_table' => 'ecommerce_transaction',
            'foreign_table_field' => 'lead_id',
            'table' => 'leads',
            'table_field' => 'id',
            'func' => 'sum',
            'field' => 'price_with_taxes',
        ]);

        $event->addTranslation('lead_transaction_purchase_product', [
            'type' => PurchaseProductFilterQueryBuilder::getServiceId(),
        ]);
    }
}
