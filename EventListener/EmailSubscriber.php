<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\EventListener;

use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\MauticEcommerceBundle\Email\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EmailSubscriber implements EventSubscriberInterface
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvents::EMAIL_ON_SEND => 'onEmailGenerate',
            EmailEvents::EMAIL_ON_DISPLAY => 'onEmailGenerate',
        ];
    }

    public function onEmailGenerate(EmailSendEvent $event): void
    {
        $lead = $event->getLead();

        $event->setContent($this->parser->parse($event->getContent(), $lead));
        $event->setPlainText($this->parser->parse($event->getPlainText(), $lead));
        $event->setSubject($this->parser->parse($event->getSubject(), $lead));
    }
}
