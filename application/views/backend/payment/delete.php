<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open($url);
?>
<p style="margin: 10px 0;"><?= $text; ?>"?</p>
<?= FORM::submit('submit', 'Удалить', array('class' => 'btn danger')).' '.FORM::submit('cancel', 'Отмена', array('class' => 'btn')); ?>
<?= FORM::close(); ?>

