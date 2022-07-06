<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

class ProductRepository extends CommonRepository
{
    public function search(string $term): array
    {
        $queryBuilder = $this->createQueryBuilder('product');

        $queryBuilder
            ->andWhere('product.name LIKE :name')
            ->setParameter('name', '%'.$term.'%')
        ;

        $queryBuilder->orderBy('product.name', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
}
