<div class="tab-pane fade bdr-w-0" id="ecommerce-transaction-container">
    <?php
        echo $view->render('MauticEcommerceBundle:Transaction:list.html.php', [
                'transactions' => $transactions,
                'lead' => $lead,
                'tmpl' => 'index',
        ]);
    ?>
</div>
