<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Sync\DataExchange;

use Mautic\IntegrationsBundle\Sync\DAO\Value\NormalizedValueDAO;
use Mautic\IntegrationsBundle\Sync\ValueNormalizer\ValueNormalizerInterface;

class ValueNormalizer implements ValueNormalizerInterface
{
    public const BOOLEAN_TYPE = 'bool';
    public const DATETIME_TYPE = 'datetime';

    public function normalizeForIntegration(NormalizedValueDAO $value)
    {
        switch ($value->getType()) {
            case NormalizedValueDAO::BOOLEAN_TYPE:
                // Integration requires actual boolean
                return (bool) $value->getNormalizedValue();
            default:
                return $value->getNormalizedValue();
        }
    }

    public function normalizeForMautic($value, $type): NormalizedValueDAO
    {
        switch ($type) {
            case self::BOOLEAN_TYPE:
                // Mautic requires 1 or 0 for booleans
                return new NormalizedValueDAO(NormalizedValueDAO::BOOLEAN_TYPE, $value, (int) $value);
            case self::DATETIME_TYPE:
                return new NormalizedValueDAO(NormalizedValueDAO::DATETIME_TYPE, $value, $value->format('Y-m-d H:i:s'));
            default:
                return new NormalizedValueDAO(NormalizedValueDAO::TEXT_TYPE, $value, (string) $value);
        }
    }
}
