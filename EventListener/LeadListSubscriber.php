<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\EventListener;

use Mautic\LeadBundle\Event\LeadListFiltersChoicesEvent;
use Mautic\LeadBundle\Event\SegmentDictionaryGenerationEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\LeadBundle\Provider\TypeOperatorProviderInterface;
use Mautic\LeadBundle\Segment\OperatorOptions;
use Mautic\LeadBundle\Segment\Query\Filter\ForeignValueFilterQueryBuilder;
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
    }

    public function onGenerateSegmentDictionary(SegmentDictionaryGenerationEvent $event): void
    {
        $event->addTranslation('lead_order_date', [
            'type' => ForeignValueFilterQueryBuilder::getServiceId(),
            'foreign_table' => 'transactions',
            'field' => 'date',
        ]);
    }
}
