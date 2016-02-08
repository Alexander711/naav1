<?php
defined('SYSPATH') or die('No direct script access.');
echo Message::show_errors($errors);
echo FORM::open($url);
?>

<fieldset style="width: 450px">
    <legend>Параметры уведомления</legend>
    <div class="clearfix">
        <label for="title">Заголовок</label>
        <div class="input">
            <?= FORM::input('title', Arr::get($values, 'title'), array('id' => 'title', 'class' => 'span5')); ?>
        </div>
    </div>
</fieldset>
<h3>Текст уведомления</h3>
<div class="clearfix">
    <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')); ?>
</div>
<?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn large success')); ?>
<?= FORM::close(); ?>