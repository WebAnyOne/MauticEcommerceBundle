<?php

declare(strict_types=1);

namespace MauticPlugin\MauticEcommerceBundle\Controller;

use Mautic\CoreBundle\Controller\AbstractFormController;
use Mautic\CoreBundle\Factory\PageHelperFactoryInterface;
use MauticPlugin\MauticEcommerceBundle\Entity\TransactionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends AbstractFormController
{
    /**
     * @param int $page
     *
     * @return JsonResponse|Response
     */
    public function indexAction($page = 1)
    {
        // controle des permissions

        $this->setListFilters('ecommerceTransaction');

        /** @var PageHelperFactoryInterface $pageHelperFacotry */
        $pageHelperFacotry = $this->get('mautic.page.helper.factory');
        $pageHelper = $pageHelperFacotry->make('mautic.ecommerceTransaction', $page);

        // on récupère la pagination
        $limit = $pageHelper->getLimit();
        $start = $pageHelper->getStart();

        // on récupère les filtres
        // on récupère le order by

        // on récupère la liste des entitités qui match tout ça
        $transactions = $this->getTransactionRepository()->getAllTransactions($start, $limit);
        // on récupère le nombre d'entité "total"
        $count = $this->getTransactionRepository()->getAllTransactionsCount();

        if ($count && $count < ($start + 1)) {
            // si on est après la dernière page, on doit rediriger sur la dernière page
            $lastPage = $pageHelper->countPage($count);
            $returnUrl = $this->generateUrl('mautic_ecommerce_transaction_index', ['page' => $lastPage]);
            $pageHelper->rememberPage($lastPage);

            return $this->postActionRedirect(
                [
                    'returnUrl' => $returnUrl,
                    'viewParameters' => ['page' => $lastPage],
                    'contentTemplate' => 'MauticEcommerceBundle:Transaction:index',
                    'passthroughVars' => [
                        'activeLink' => '#mautic_ecommerce_transaction_index',
                        'mauticContent' => 'ecommerceTransaction',
                    ],
                ]
            );
        }

        $pageHelper->rememberPage($page);

        // template différent si requete ajax ?
        $tmpl = $this->request->isXmlHttpRequest() ? $this->request->get('tmpl', 'index') : 'index';

        return $this->delegateView(
            [
                'viewParameters' => [
                    'items' => $transactions,
                    'page' => $page,
                    'limit' => $limit,
                    'tmpl' => $tmpl,
                    'totalItems' => $count,
                ],
                'contentTemplate' => 'MauticEcommerceBundle:Transaction:list.html.php',
                'passthroughVars' => [
                    'activeLink' => '#mautic_ecommerce_transaction_index',
                    'mauticContent' => 'ecommerceTransaction',
                    'route' => $this->generateUrl('mautic_ecommerce_transaction_index', ['page' => $page]),
                ],
            ]
        );
    }

    private function getTransactionRepository(): TransactionRepository
    {
        return $this->get('mautic_ecommerce.repository.transaction');
    }
}
