<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open($url);
?>
<h3>Текст</h3>
Город: <?= $content->city->name; ?>
<div class="clearfix">
    <?= FORM::textarea('text', $values['text'], array('id' => 'text')); ?>
</div>

<?= TbForm::actions('save', array('horizontal' => FALSE)); ?>
<?= FORM::close(); ?>