<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Mautic\LeadBundle\Entity\Lead;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
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

    /**
     * @param array|Lead $lead
     */
    public function findLatest($lead): ?Transaction
    {
        if ($lead instanceof Lead) {
            $id = $lead->getId();
        } elseif (\is_array($lead)) {
            $id = $lead['id'];
        } else {
            throw new \RuntimeException('Unable to retrieve the lead');
        }

        return $this->findOneBy(['lead' => $id], ['date' => 'DESC']);
    }
}
