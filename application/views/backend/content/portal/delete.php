<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open('admin/content/portal/delete/'.$content->id);
?>

<p style="margin: 10px 0;">Вы действительно хотите удалить страницу "<?= $content->title; ?>"?</p>
<?= FORM::submit('submit', 'Удалить', array('class' => 'delete')).' '.FORM::submit('cancel', 'Отмена'); ?>
<?= FORM::close(); ?>

