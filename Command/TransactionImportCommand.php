<?php

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
        } while (count($orders) > 0);

        // on récupère toute les commandes depuis l'api
        // pour chacune on doit créer une transaction et la lié a un lead
            // comment on fait pour trouver le bon lead ?
                // L'info est dans la table sync_object_mapping
                // mais si l'objet n'existe pas ? -> on peut commencer par ignorer la transaction et elle sera présente le prochain couo
                // il n'y a pas un objet pour faire ça ?
                // \Mautic\IntegrationsBundle\Entity\ObjectMappingRepository::getInternalObject('Prestashop', 'customer', $customerId, 'lead')

        // Comment on gère la mise à jour ?

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
