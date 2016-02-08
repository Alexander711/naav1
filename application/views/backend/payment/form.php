<?php
defined('SYSPATH') or die('No direct script access.');

echo Message::show_errors($errors);
echo FORM::open($url, array('autocomplete' => 'off', 'class' => 'form-horizontal'));
?>

<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label class="control-label">Название</label>
        <div class="controls">
            <?= FORM::input('payment_name', Arr::get($values, 'payment_name')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'payment_name'); ?></p>
        </div>
    </div>
	<div class="control-group">
        <label class="control-label">Подсказка</label>
        <div class="controls">
            <?= FORM::input('tips', Arr::get($values, 'tips')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'tips'); ?></p>
        </div>

    </div>
	<div class="control-group">
        <label class="control-label">Описание</label>
        <div class="controls">
            <?= FORM::textarea('description', Arr::get($values, 'description')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'description'); ?></p>
        </div>

    </div>

	<div class="control-group">
        <label class="control-label">Статус</label>
        <div class="controls">
	        <?= FORM::select('status', array('Y'=>'Показать','N'=>'Скрыть'), Arr::get($values, 'status','Y')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'status'); ?></p>
        </div>

    </div>
	<div class="control-group">
        <label class="control-label">Сортировка</label>
        <div class="controls">
            <?= FORM::input('position', Arr::get($values, 'position',1)); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'position'); ?></p>
        </div>

    </div>
    <div class="form-actions">
        <?= FORM::hidden('id', Arr::get($values, 'id')); ?>
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-success btn-large')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>




