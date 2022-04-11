<?php

declare(strict_types=1);

namespace MauticPlugin\WebAnyOneMauticPrestashopBundle\Command;

use Mautic\IntegrationsBundle\Entity\ObjectMappingRepository;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Api\ClientFactory;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity\Transaction;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Entity\TransactionRepository;
use MauticPlugin\WebAnyOneMauticPrestashopBundle\Integration\PrestashopIntegration;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionImportCommand extends Command
{
    public static $defaultName = 'webanyone:prestashop:transaction:import';

    private ClientFactory $clientFactory;

    private ObjectMappingRepository $objectMappingRepository;

    private ManagerRegistry $registry;

    private TransactionRepository $transactionRepository;

    public function __construct(
        ClientFactory $clientFactory,
        ObjectMappingRepository $objectMappingRepository,
        TransactionRepository $transactionRepository,
        ManagerRegistry $registry
    ) {
        parent::__construct();
        $this->clientFactory = $clientFactory;
        $this->objectMappingRepository = $objectMappingRepository;
        $this->transactionRepository = $transactionRepository;
        $this->registry = $registry;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->registry->getManager();
        $client = $this->clientFactory->getClient();

        $page = 1;

        do {
            $orders = $client->getOrders($page++, 10);

            foreach ($orders as $order) {
                $this->processOrder($order);
            }

            $entityManager->flush();
            $entityManager->clear();
        } while (\count($orders) > 0);

        return 1;
    }

    private function processOrder(array $order): void
    {
        $lead = $this->objectMappingRepository->getInternalObject(
            PrestashopIntegration::NAME,
            'customer',
            $order['id_customer'],
            'lead'
        );

        $this->transactionRepository->createOrUpdate(Transaction::fromOrderArray($lead['internal_object_id'], $order));
    }
}
