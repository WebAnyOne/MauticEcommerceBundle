<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Tests\Email;

use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Email\TransactionProductsParser;
use MauticPlugin\MauticEcommerceBundle\Entity\Product;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransactionProductsParserTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider provideTestParse
     */
    public function testParse(Transaction $transaction, string $content, string $expectedContent): void
    {
        $parser = new TransactionProductsParser();

        $result = $parser->parse($content, $transaction);

        self::assertEquals($expectedContent, $result);
    }

    public function provideTestParse(): iterable
    {
        $productA = new Product();
        $productA->setName('Product A');
        $productA->setUnitPrice(1200);

        \Closure::bind(function () use ($productA) {
            $productA->id = 1;
        }, $productA, Product::class)();

        $productB = new Product();
        $productB->setName('Product B');
        $productB->setUnitPrice(1000);

        \Closure::bind(function () use ($productB) {
            $productB->id = 2;
        }, $productB, Product::class)();

        $lead = $this->prophesize(Lead::class);
        $transaction = new Transaction($lead->reveal(), 1, new \DateTimeImmutable(), 0, 0, 0);

        yield 'empty' => [$transaction, '', ''];
        yield 'no products' => [
            $transaction,
            <<<HTML
<ul>{transaction_products}<li>{product:name} / {product:unitPrice} / {product:quantity}</li>{/transaction_products}</ul>
HTML,
            <<<HTML
<ul></ul>
HTML,
        ];

        $transaction = new Transaction($lead->reveal(), 1, new \DateTimeImmutable(), 3200, 3200, 2);
        $transaction->addProduct($productA, 2);
        $transaction->addProduct($productB, 1);

        yield 'valid' => [
            $transaction,
            <<<HTML
<ul>
    {transaction_products}
    <li>{product:name} / {product:unit_price} / {product:quantity}</li>
    {/transaction_products}
</ul>
HTML,
            <<<HTML
<ul>
    
    <li>Product A / 12 / 2</li>
    
    <li>Product B / 10 / 1</li>
    
</ul>
HTML,
        ];
        yield 'no product tags' => [
            $transaction,
            <<<HTML
<ul>
    {transaction_products}<li>A</li>{/transaction_products}
</ul>
HTML,
            <<<HTML
<ul>
    <li>A</li><li>A</li>
</ul>
HTML,
        ];
    }
}
