<?php

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Sync\DataExchange;

use Mautic\IntegrationsBundle\Sync\DAO\Value\NormalizedValueDAO;
use Mautic\IntegrationsBundle\Sync\ValueNormalizer\ValueNormalizerInterface;

class ValueNormalizer implements ValueNormalizerInterface
{
    const BOOLEAN_TYPE = 'bool';

    public function normalizeForIntegration(NormalizedValueDAO $value)
    {
        // @todo handle date fields ?

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
            default:
                return new NormalizedValueDAO(NormalizedValueDAO::TEXT_TYPE, $value, (string) $value);
        }
    }
}
