<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\EventListener;

use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\MauticEcommerceBundle\Email\Parser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailSubscriber implements EventSubscriberInterface
{
    private Parser $parser;
    private TranslatorInterface $translator;

    public function __construct(Parser $parser, TranslatorInterface $translator)
    {
        $this->parser = $parser;
        $this->translator = $translator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            EmailEvents::EMAIL_ON_SEND => 'onEmailGenerate',
            EmailEvents::EMAIL_ON_DISPLAY => 'onEmailGenerate',
            EmailEvents::EMAIL_ON_BUILD => 'onEmailBuild',
        ];
    }

    public function onEmailGenerate(EmailSendEvent $event): void
    {
        $lead = $event->getLead();

        $event->setContent($this->parser->parse($event->getContent(), $lead));
        $event->setPlainText($this->parser->parse($event->getPlainText(), $lead));
        $event->setSubject($this->parser->parse($event->getSubject(), $lead));
    }

    public function onEmailBuild(EmailBuilderEvent $event): void
    {
        $tokens = [
            '{transaction:price}' => $this->translator->trans('mautic_ecommerce.email.token.transaction.price'),
            '{transaction:nb_products}' => $this->translator->trans('mautic_ecommerce.email.token.transaction.nb_products'),
            '{transaction:date}' => $this->translator->trans('mautic_ecommerce.email.token.transaction.date'),
            '{product:name}' => $this->translator->trans('mautic_ecommerce.email.token.product.name'),
            '{product:quantity}' => $this->translator->trans('mautic_ecommerce.email.token.product.quantity'),
            '{product:unit_price}' => $this->translator->trans('mautic_ecommerce.email.token.product.unit_price'),
        ];

        if ($event->tokensRequested(array_keys($tokens))) {
            $event->addTokens(
                $event->filterTokens($tokens)
            );
        }
    }
}
