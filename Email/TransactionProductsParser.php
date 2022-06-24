<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email;

use MauticPlugin\MauticEcommerceBundle\Email\Helper\ParamsHelper;
use MauticPlugin\MauticEcommerceBundle\Email\Tag\TransactionProductTag;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionProduct;

class TransactionProductsParser
{
    public function parse(string $content, Transaction $transaction): string
    {
        preg_match('/{transaction_products([^}]*)}(.*){\/transaction_products}/ms', $content, $transactionProductMatches);

        if (empty($transactionProductMatches)) {
            return $content;
        }

        // todo handle params on transaction_products to change order of products

        $template = $transactionProductMatches[2];
        $itemsContent = '';

        foreach ($transaction->getProducts() as $transactionProduct) {
            $itemsContent .= $this->parseItem($template, $transactionProduct);
        }

        return str_replace($transactionProductMatches[0], $itemsContent, $content);
    }

    private function parseItem(string $template, TransactionProduct $transactionProduct): string
    {
        preg_match_all('/{product:([^ }:]+)(:([^ }:]+))?( [^}]+)?}/', $template, $tags);

        if (empty($tags)) {
            return $template;
        }

        foreach ($tags[1] as $tagIndex => $tag) {
            $transactionProductTag = new TransactionProductTag($transactionProduct, $tag, ParamsHelper::parse($tags[3][$tagIndex] ?? ''));

            $template = str_replace($tags[0][$tagIndex], $transactionProductTag->getValue(), $template);
        }

        return $template;
    }
}
