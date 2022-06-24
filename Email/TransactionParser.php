<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email;

use MauticPlugin\MauticEcommerceBundle\Email\Helper\ParamsHelper;
use MauticPlugin\MauticEcommerceBundle\Email\Tag\TransactionTag;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;

class TransactionParser
{
    private TransactionProductsParser $transactionProductsParser;

    public function __construct()
    {
        $this->transactionProductsParser = new TransactionProductsParser();
    }

    public function parse(string $content, Transaction $transaction): string
    {
        $content = $this->parseTransaction($content, $transaction);

        return $this->transactionProductsParser->parse($content, $transaction);
    }

    private function parseTransaction(string $content, Transaction $transaction): string
    {
        preg_match_all('/{transaction:([^ }]+)( [^}]+)?}/', $content, $tags);

        if (empty($tags[1])) {
            return $content;
        }

        foreach ($tags[1] as $tagIndex => $tag) {
            $transactionTag = new TransactionTag($transaction, $tag, ParamsHelper::parse($tags[2][$tagIndex] ?? ''));

            $content = str_replace($tags[0][$tagIndex], $transactionTag->getValue(), $content);
        }

        return $content;
    }
}
