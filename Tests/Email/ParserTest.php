<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Tests\Email;

use Mautic\LeadBundle\Entity\Lead;
use MauticPlugin\MauticEcommerceBundle\Email\Parser;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ParserTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider provideTestParse
     */
    public function testParse(?Transaction $transaction, string $content, string $expectedContent): void
    {
        $lead = $this->prophesize(Lead::class);

        $repository = $this->prophesize(TransactionRepository::class);
        $repository->findLatest($lead->reveal())->willReturn($transaction);

        $parser = new Parser($repository->reveal());
        $result = $parser->parse($content, $lead->reveal());

        self::assertEquals($expectedContent, $result);
    }

    public function provideTestParse(): iterable
    {
        $lead = $this->prophesize(Lead::class);
        $transaction = new Transaction($lead->reveal(), 1, new \DateTimeImmutable(), 3200, 3200, 2);

        yield 'empty' => [$transaction, '', ''];
        yield 'no transaction found' => [null, '{last_transaction}test{/last_transaction}', ''];
        yield 'valid' => [$transaction, '{last_transaction}test{/last_transaction}', 'test'];
    }
}
