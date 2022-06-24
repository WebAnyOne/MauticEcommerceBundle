<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Tests\Email\Tag;

use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Email\Tag\TransactionTag;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTagTest extends TestCase
{
    /**
     * @dataProvider provideTestGetValue
     */
    public function testGetValue(Transaction $transaction, string $tag, array $params, string $expectedValue): void
    {
        $value = new TransactionTag($transaction, $tag, $params);

        self::assertEquals($expectedValue, $value->getValue());
    }

    public function provideTestGetValue(): iterable
    {
        $lead = $this->prophesize(Lead::class);

        $transaction = new Transaction(
            $lead->reveal(),
            1,
            new \DateTimeImmutable('2022-01-01T20:13:58.000Z'),
            1000,
            1200,
            2
        );

        yield 'price' => [$transaction, 'price', [], '12'];
        yield 'nb_products' => [$transaction, 'nb_products', [], '2'];
        yield 'date without format' => [$transaction, 'date', [], '01/01/2022 20:13:58'];
        yield 'date with format' => [$transaction, 'date', ['format' => 'Y-m-d'], '2022-01-01'];
        yield 'unknown field' => [$transaction, 'unknown', [], 'Unknown (unknown)'];
    }
}
