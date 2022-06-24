<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email\Helper;

use PHPUnit\Framework\TestCase;

class ParamsHelperTest extends TestCase
{
    /**
     * @dataProvider provideTestParse
     */
    public function testParse(string $input, array $expectedParams): void
    {
        $params = ParamsHelper::parse($input);

        self::assertEquals($expectedParams, $params);
    }

    public function provideTestParse(): iterable
    {
        yield 'empty' => ['', []];
        yield 'one param' => ['foo="bar"', ['foo' => 'bar']];
        yield 'more params' => ['foo="bar" bar="baz"', ['foo' => 'bar', 'bar' => 'baz']];
    }
}
