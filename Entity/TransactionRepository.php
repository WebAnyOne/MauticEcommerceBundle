<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function createOrUpdate(Transaction $transaction): void
    {
        /** @var Transaction|null $existingTransaction */
        $existingTransaction = $this->find($transaction->getId());
        if ($existingTransaction === null) {
            $this->getEntityManager()->persist($transaction);

            return;
        }

        $existingTransaction->update($transaction);
    }
}
