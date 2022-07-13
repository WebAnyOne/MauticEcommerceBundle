<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Tests\Email;

use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Email\TransactionParser;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class TransactionParserTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider provideTestParse
     */
    public function testParse(string $content, string $expecterdOutput): void
    {
        $lead = $this->prophesize(Lead::class);
        $transaction = new Transaction($lead->reveal(), 1, new \DateTimeImmutable('2022-06-28'), 3200, 3200, 2);
        $parser = new TransactionParser();
        $result = $parser->parse($content, $transaction);

        self::assertEquals($expecterdOutput, $result);
    }

    public function provideTestParse(): iterable
    {
        yield 'empty' => ['', ''];
        yield 'no transaction tags' => ['test', 'test'];
        yield 'unknow transaction tag' => ['{transaction:test}', 'Unknown (test)'];
        yield '1 transaction tag' => ['{transaction:date format="Y-m-d"}', '2022-06-28'];
        yield 'multiple transaction tags' => ['{transaction:date format="Y-m-d"} / {transaction:price}', '2022-06-28 / 32'];
    }
}
