<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\EventListener;

use Mautic\CoreBundle\CoreEvents;
use Mautic\CoreBundle\Event\CustomContentEvent;
use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LeadUiSubscriber implements EventSubscriberInterface
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CoreEvents::VIEW_INJECT_CUSTOM_CONTENT => 'onLeadDetailRender',
        ];
    }

    public function onLeadDetailRender(CustomContentEvent $event): void
    {
//        dump(['viewName' => $event->getViewName(), 'context' => $event->getContext()]);
        if ($event->getViewName() !== 'MauticLeadBundle:Lead:lead.html.php') {
            return;
        }
        $lead = $event->getVars()['lead'];

        // retrieve transactions from $eveng

        switch ($event->getContext()) {
            case 'tabs':
                $this->renderContactTabHeader($event, $lead);
                break;
            case 'tabs.content':
                $this->renderContactTabContent($event, $lead);
                break;
        }
    }

    private function renderContactTabHeader(CustomContentEvent $event, Lead $lead): void
    {
        $count = $this->transactionRepository->getTransactionsCount($lead);

        $event->addTemplate('MauticEcommerceBundle:Contact:tab.html.php', ['count' => $count]);
    }

    private function renderContactTabContent(CustomContentEvent $event, Lead $lead): void
    {
        $transactions = $this->transactionRepository->getTransactions($lead);

        $event->addTemplate('MauticEcommerceBundle:Contact:tabContent.html.php', [
            'lead' => $lead,
            'items' => $transactions,
            'totalItems' => $this->transactionRepository->getTransactionsCount($lead),
            'page' => 1,
            'limit' => $this->transactionRepository->getTransactionsCount($lead),
        ]);
    }
}
