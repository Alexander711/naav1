<?php
defined('SYSPATH') or die('No direct script access.');

echo Message::show_errors($errors);
echo FORM::open($url, array('autocomplete' => 'off', 'class' => 'form-horizontal'));
?>

<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label class="control-label">Пользователь</label>
        <div class="controls">
            <strong><?= Arr::get($values, 'username'); ?></strong>
        </div>
    </div>
	<div class="control-group">
        <label class="control-label">Платежная система</label>
        <div class="controls">
            <strong><?= Arr::get($values, 'payment_name'); ?></strong>
        </div>
    </div>
	<div class="control-group">
        <label class="control-label">Сумма</label>
        <div class="controls">
            <?= FORM::input('amount', Arr::get($values, 'amount')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'amount'); ?></p>
        </div>

    </div>

	<div class="control-group">
        <label class="control-label">Кол-во дней</label>
        <div class="controls">
            <?= FORM::input('days_amount', Arr::get($values, 'days_amount')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'days_amount'); ?></p>
        </div>

    </div>

	<div class="control-group">
        <label class="control-label">Статус</label>
        <div class="controls">
	        <?= FORM::select('status', array("N"=>"Новый","P"=>"Оплачен","C"=>"Отменен","E"=>"Просрочен"), Arr::get($values, 'status','N')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'status'); ?></p>
        </div>

    </div>

	<div class="control-group">
        <label class="control-label">Вступ. взнос</label>
        <div class="controls">
	        <?= FORM::select('entrance_fee', array('Y'=>'Да','N'=>'Нет'), Arr::get($values, 'entrance_fee','N')); ?>
            <p class="help-block" style="color: red;"><?= Arr::get($errors, 'entrance_fee'); ?></p>
        </div>

    </div>

    <div class="form-actions">
        <?= FORM::hidden('id', Arr::get($values, 'id')); ?>
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-success btn-large')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>




