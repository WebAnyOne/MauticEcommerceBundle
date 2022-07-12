<?php

namespace MauticPlugin\MauticEcommerceBundle\Segment\Query\Filter;

use Mautic\LeadBundle\Segment\ContactSegmentFilter;
use Mautic\LeadBundle\Segment\Query\Filter\FilterQueryBuilderInterface;
use Mautic\LeadBundle\Segment\Query\QueryBuilder;

class PurchaseProductFilterQueryBuilder implements FilterQueryBuilderInterface
{
    public function applyQuery(QueryBuilder $queryBuilder, ContactSegmentFilter $filter): QueryBuilder
    {
        $leadsTableAlias = $queryBuilder->getTableAlias(MAUTIC_TABLE_PREFIX.'leads');
        $filterOperator  = $filter->getOperator();

        $subQuery = 'SELECT lead_id 
                     FROM ecommerce_transaction 
                         INNER JOIN ecommerce_transaction_product
                             ON ecommerce_transaction.id = ecommerce_transaction_product.transaction_id
                                    AND  ecommerce_transaction_product.product_id = :productId';

        $queryBuilder
            ->andWhere($leadsTableAlias. '.id IN (' . $subQuery . ')')
            ->setParameter('productId', $filter->getParameterValue())
        ;

        switch ($filterOperator) {
            case 'eq':
                $queryBuilder
                    ->andWhere($leadsTableAlias. '.id IN (' . $subQuery . ')')
                    ->setParameter('productId', $filter->getParameterValue())
                ;
                break;
            case 'neq':
                $queryBuilder
                    ->andWhere($leadsTableAlias. '.id NOT IN (' . $subQuery . ')')
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
