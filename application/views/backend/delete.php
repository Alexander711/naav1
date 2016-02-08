<?php
defined('SYSPATH') or die('No direct script access.');
$from_url = (!isset($from_url)) ? '#' : $from_url;
echo FORM::open(Request::current()->url());
?>
<div class="alert alert-block alert-error fade in">
    <?= HTML::anchor($from_url, '&times;', array('class' => 'close')); ?>
    <h4 class="alert-heading"><?= @$title; ?></h4>
    <p><?= $text; ?></p>
    <p>
        <?= FORM::submit('submit', 'Удалить', array('class' => 'btn btn-danger small')).' '.FORM::submit('cancel', 'Отмена', array('class' => 'btn small')); ?>
    </p>
</div>
<?= FORM::close(); ?>

