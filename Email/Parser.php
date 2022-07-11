<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email;

use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionRepository;

class Parser
{
    private TransactionRepository $transactionRepository;
    private TransactionParser $transactionParser;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
        $this->transactionParser = new TransactionParser();
    }

    /**
     * @param array|Lead $lead
     */
    public function parse(string $content, $lead): string
    {
        preg_match_all('/{last_transaction((?:[^{}]|{[^}]*})*)}(.*){\/last_transaction}/msU', $content, $matches);

        if (empty($matches[0])) {
            return $content;
        }

        $transaction = $this->transactionRepository->findLatest($lead);

        foreach ($matches[0] as $key => $transactionWrapper) {
            $transactionContent = $matches[2][$key];

            if ($transaction !== null) {
                $parsedContent = $this->transactionParser->parse($transactionContent, $transaction);
            }

            $content = str_replace($transactionWrapper, $parsedContent ?? '', $content);
        }

        return $content;
    }
}
