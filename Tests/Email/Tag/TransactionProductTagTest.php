<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Tests\Email\Tag;

use MauticPlugin\MauticEcommerceBundle\Email\Tag\TransactionProductTag;
use MauticPlugin\MauticEcommerceBundle\Entity\Product;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionProduct;
use PHPUnit\Framework\TestCase;

class TransactionProductTagTest extends TestCase
{
    /**
     * @dataProvider provideTestGetValue
     */
    public function testGetValue(TransactionProduct $transactionProduct, string $tag, array $params, string $expectedValue): void
    {
        $value = new TransactionProductTag($transactionProduct, $tag, $params);

        self::assertEquals($expectedValue, $value->getValue());
    }

    public function provideTestGetValue(): iterable
    {
        $product = new Product();
        $product->setName('Product name');
        $product->setUnitPrice(1000);

        $transaction = $this->prophesize(Transaction::class);
        $transactionProduct = new TransactionProduct($transaction->reveal(), $product, 2);

        yield 'name' => [$transactionProduct, 'name', [], 'Product name'];
        yield 'quantity' => [$transactionProduct, 'quantity', [], '2'];
        yield 'unit price' => [$transactionProduct, 'unit_price', [], '10'];
        yield 'unknown field' => [$transactionProduct, 'unknown', [], 'Unknown (unknown)'];
    }
}
