<?php declare(strict_types=1);
if (isset($tmpl) && 'index' == $tmpl) {
    $view->extend('MauticEcommerceBundle:Transaction:index.html.php');
}
?>

<?php if (\count($items)) { ?>
    <div class="table-responsive page-list">
        <table class="table table-hover table-striped table-bordered company-list" id="companyTable">
            <thead>
                <tr>
                    <?php
                        echo $view->render(
                            'MauticCoreBundle:Helper:tableheader.html.php',
                            [
                                'sessionVar' => 'ecommerceTransaction',
                                'text' => 'mautic_ecommerce.transaction.table.lead',
                                'class' => 'col-ecommerce-transaction-lead',
                                'orderBy' => 'lead.name',
                            ]
                        );
                        echo $view->render(
                            'MauticCoreBundle:Helper:tableheader.html.php',
                            [
                                'sessionVar' => 'ecommerceTransaction',
                                'text' => 'mautic_ecommerce.transaction.table.status',
                                'class' => 'col-ecommerce-transaction-status',
                                'orderBy' => 't.status',
                            ]
                        );
                        echo $view->render(
                            'MauticCoreBundle:Helper:tableheader.html.php',
                            [
                                'sessionVar' => 'ecommerceTransaction',
                                'text' => 'mautic_ecommerce.transaction.table.date',
                                'class' => 'col-ecommerce-transaction-date',
                                'orderBy' => 't.date',
                            ]
                        );
                        echo $view->render(
                            'MauticCoreBundle:Helper:tableheader.html.php',
                            [
                                'sessionVar' => 'ecommerceTransaction',
                                'text' => 'mautic_ecommerce.transaction.table.nbProducts',
                                'class' => 'col-ecommerce-transaction-nb-products',
                                'orderBy' => 't.status',
                            ]
                        );
                        echo $view->render(
                            'MauticCoreBundle:Helper:tableheader.html.php',
                            [
                                'sessionVar' => 'ecommerceTransaction',
                                'text' => 'mautic_ecommerce.transaction.table.priceWithoutTaxes',
                                'class' => 'col-ecommerce-transaction-price-without-taxes',
                                'orderBy' => 't.status',
                            ]
                        );
                        echo $view->render(
                            'MauticCoreBundle:Helper:tableheader.html.php',
                            [
                                'sessionVar' => 'ecommerceTransaction',
                                'text' => 'mautic_ecommerce.transaction.table.priceWithTaxes',
                                'class' => 'col-ecommerce-transaction-price-with-taxes',
                                'orderBy' => 't.status',
                            ]
                        );
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?php echo $item->getLead()->getName(); ?></td>
                        <td></td>
                        <td><?php echo $view['date']->toShort($item->getDate()); ?></td>
                        <td><?php echo $item->getNbProducts(); ?></td>
                        <td><?php echo $view['ecommerce_money']->format($item->getPriceWithoutTaxes()); ?></td>
                        <td><?php echo $view['ecommerce_money']->format($item->getPriceWithTaxes()); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <?php echo $view->render(
            'MauticCoreBundle:Helper:pagination.html.php',
            [
                'totalItems' => $totalItems,
                'page' => $page,
                'limit' => $limit,
                'menuLinkId' => 'mautic_ecommerce_transaction_index',
                'baseUrl' => $view['router']->url('mautic_ecommerce_transaction_index'),
                'sessionVar' => 'ecommerceTransaction',
            ]
        ); ?>
    </div>
<?php } else { ?>
    <?php echo $view->render('MauticCoreBundle:Helper:noresults.html.php'); ?>
<?php } ?>
