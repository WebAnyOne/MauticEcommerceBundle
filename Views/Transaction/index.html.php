<?php declare(strict_types=1);

$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'ecommerceTransaction');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic_ecommerce.transaction.title'));

?>

<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php echo $view->render(
        'MauticCoreBundle:Helper:list_toolbar.html.php',
        [
            'searchHelp' => 'mautic.core.help.searchcommands',
            'action' => '',
        ]
    ); ?>
    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>
