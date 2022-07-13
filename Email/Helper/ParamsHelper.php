<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Email\Helper;

class ParamsHelper
{
    public static function parse(string $paramsString): array
    {
        preg_match_all('/ ?([^=]+)="([^"]+)"/', $paramsString, $matches);

        return (array) array_combine($matches[1], $matches[2]);
    }
}
