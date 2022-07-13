<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Segment\Query\Filter;

use Mautic\LeadBundle\Segment\ContactSegmentFilter;
use Mautic\LeadBundle\Segment\Query\Filter\FilterQueryBuilderInterface;
use Mautic\LeadBundle\Segment\Query\QueryBuilder;

class PurchaseProductFilterQueryBuilder implements FilterQueryBuilderInterface
{
    public function applyQuery(QueryBuilder $queryBuilder, ContactSegmentFilter $filter): QueryBuilder
    {
        $tablePrefix = MAUTIC_TABLE_PREFIX ?? '';

        $leadsTableAlias = (string) $queryBuilder->getTableAlias($tablePrefix . 'leads');
        $filterOperator = $filter->getOperator();

        $subQuery = "SELECT lead_id 
                     FROM {$tablePrefix}ecommerce_transaction ppf_transaction
                         INNER JOIN {$tablePrefix}ecommerce_transaction_product as ppf_transaction_product
                             ON ppf_transaction.id = ppf_transaction_product.transaction_id
                                    AND  ppf_transaction_product.product_id = :productId";

        $queryBuilder
            ->andWhere("$leadsTableAlias.id IN ($subQuery)")
            ->setParameter('productId', $filter->getParameterValue())
        ;

        switch ($filterOperator) {
            case 'eq':
                $queryBuilder
                    ->andWhere("$leadsTableAlias.id IN ($subQuery)")
                    ->setParameter('productId', $filter->getParameterValue())
                ;
                break;
            case 'neq':
                $queryBuilder
                    ->andWhere("$leadsTableAlias.id NOT IN ($subQuery)")
                    ->setParameter('productId', $filter->getParameterValue())
                ;
                break;
        }

        return $queryBuilder;
    }

    public static function getServiceId(): string
    {
        return 'mautic_ecommerce.lead.query_builder.purchase_product';
    }
}
