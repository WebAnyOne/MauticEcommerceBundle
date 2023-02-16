<div class="tab-pane fade bdr-w-0" id="ecommerce-transaction-container">
    <?php
        echo $view->render('MauticEcommerceBundle:Transaction:list.html.php', [
                'items' => $items,
                'totalItems' => $totalItems,
                'lead' => $lead,
                'tmpl' => 'index',
                'page' => 1,
                'limit' => $limit,
        ]);
    ?>
</div>
