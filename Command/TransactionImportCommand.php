<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Command;

use Mautic\IntegrationsBundle\Entity\ObjectMappingRepository;
use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionRepository;
use MauticPlugin\MauticEcommerceBundle\Model\Order;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TransactionImportCommand extends Command
{
    public static $defaultName = 'ecommerce:transaction:import';

    private ObjectMappingRepository $objectMappingRepository;

    private ManagerRegistry $registry;

    private TransactionRepository $transactionRepository;

    private IntegrationsHelper $integrationsHelper;

    public function __construct(
        IntegrationsHelper $integrationsHelper,
        ObjectMappingRepository $objectMappingRepository,
        TransactionRepository $transactionRepository,
        ManagerRegistry $registry
    ) {
        parent::__construct();
        $this->integrationsHelper = $integrationsHelper;
        $this->objectMappingRepository = $objectMappingRepository;
        $this->transactionRepository = $transactionRepository;
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->addArgument('integrationName', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $integrationName = $input->getArgument('integrationName');

        $integration = $this->integrationsHelper->getIntegration($integrationName);

        $entityManager = $this->registry->getManager();
        $client = $integration->getClient();

        $page = 1;

        do {
            $orders = $client->getOrders($page++, 10);

            foreach ($orders as $order) {
                $this->processOrder($integrationName, $order);
            }

            $entityManager->flush();
            $entityManager->clear();
        } while (\count($orders) > 0);

        return 1;
    }

    private function processOrder(string $integrationName, Order $order): void
    {
        $lead = $this->objectMappingRepository->getInternalObject(
            $integrationName,
            'customer',
            $order->customerId,
            'lead'
        );

        if ($lead === null) {
            // no lead found
            return;
        }

        $this->transactionRepository->createOrUpdate(Transaction::fromOrder($lead['internal_object_id'], $order));
    }
}
