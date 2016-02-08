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
            <?= FORM::input('name', Arr::get($values, 'name')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'name'); ?></p>
        </div>
    </div>
	<div class="control-group">
        <label class="control-label">Значение</label>
        <div class="controls">
            <?= FORM::textarea('value', Arr::get($values, 'value')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'value'); ?></p>
        </div>

    </div>
	<div class="control-group">
        <label class="control-label">Цена</label>
        <div class="controls">
            <?= FORM::input('price', Arr::get($values, 'price')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'price'); ?></p>
        </div>

    </div>
	<div class="control-group">
        <label class="control-label">Дней</label>
        <div class="controls">
            <?= FORM::input('days', Arr::get($values, 'days')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'days'); ?></p>
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
        <label class="control-label">В списке</label>
	        <div class="controls">
		        <?= FORM::select('in_list', array('Y'=>'Показать','N'=>'Скрыть'), Arr::get($values, 'in_list','Y')); ?>
	            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'in_list'); ?></p>
	        </div>

	    </div>
	<div class="control-group">
        <label class="control-label">Сортировка</label>
        <div class="controls">
            <?= FORM::input('sort', Arr::get($values, 'sort',1)); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'sort'); ?></p>
        </div>

    </div>
    <div class="form-actions">
        <?= FORM::hidden('id', Arr::get($values, 'id')); ?>
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-success btn-large')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>




