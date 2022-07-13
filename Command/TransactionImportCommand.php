<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Command;

use Mautic\IntegrationsBundle\Entity\ObjectMappingRepository;
use Mautic\IntegrationsBundle\Helper\IntegrationsHelper;
use Mautic\LeadBundle\Entity\LeadRepository;
use MauticPlugin\MauticEcommerceBundle\Entity\ProductRepository;
use MauticPlugin\MauticEcommerceBundle\Entity\Transaction;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionRepository;
use MauticPlugin\MauticEcommerceBundle\Integration\EcommerceAbstractIntegration;
use MauticPlugin\MauticEcommerceBundle\Model\Order;
use MauticPlugin\MauticEcommerceBundle\Sync\Mapping\Manual\MappingManualFactory;
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
    private LeadRepository $leadRepository;
    private ProductRepository $productRepository;

    public function __construct(
        IntegrationsHelper $integrationsHelper,
        ObjectMappingRepository $objectMappingRepository,
        TransactionRepository $transactionRepository,
        LeadRepository $leadRepository,
        ProductRepository $productRepository,
        ManagerRegistry $registry
    ) {
        parent::__construct();
        $this->integrationsHelper = $integrationsHelper;
        $this->objectMappingRepository = $objectMappingRepository;
        $this->transactionRepository = $transactionRepository;
        $this->registry = $registry;
        $this->leadRepository = $leadRepository;
        $this->productRepository = $productRepository;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('integrationName', InputArgument::REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $integrationName = $input->getArgument('integrationName');

        $integration = $this->integrationsHelper->getIntegration($integrationName);

        if (!$integration instanceof EcommerceAbstractIntegration) {
            throw new \Exception(
                sprintf('The integration %s must extends %s', $integrationName, EcommerceAbstractIntegration::class)
            );
        }

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
            MappingManualFactory::CUSTOMER_OBJECT,
            $order->customerId,
            'lead'
        );

        if ($lead === null) {
            // no lead found
            return;
        }

        $lead = $this->leadRepository->getEntity($lead['internal_object_id']);

        $transaction = Transaction::fromOrder($lead, $order);

        foreach ($order->products as $orderProduct) {
            $internalProduct = $this->objectMappingRepository->getInternalObject(
                $integrationName,
                MappingManualFactory::PRODUCT_OBJECT,
                $orderProduct->productId,
                'product'
            );

            if ($internalProduct === null) {
                continue;
            }

            $productEntity = $this->productRepository->find($internalProduct['internal_object_id']);
            if ($productEntity === null) {
                continue;
            }

            $transaction->addProduct($productEntity, $orderProduct->quantity);
        }

        $this->transactionRepository->createOrUpdate($transaction);
    }
}
