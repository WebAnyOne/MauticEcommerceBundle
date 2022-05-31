<?php
if (isset($tmpl) && 'index' == $tmpl) {
    $view->extend('MauticEcommerceBundle:Transaction:index.html.php');
}
?>

<?php dump($transactions); ?>
<div class="table-responsive">
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
            </tr>
        </thead>
    </table>
</div>
