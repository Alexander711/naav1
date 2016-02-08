<?php
defined('SYSPATH') or die('No direct script access.');
echo Message::show_errors($errors);
echo FORM::open($url);
?>
<fieldset>
    <legend>Параметры модели автомобиля</legend>
    <div class="clearfix">
        <label for="name">Название модели</label>
        <div class="input">
            <?= FORM::input('name', Arr::get($values, 'name'), array('id' => 'name', 'class' => 'span5')); ?>
        </div>
    </div>
    <div class="clearfix">
        <label for="car_id">Марка автомобиля</label>
        <div class="input">
            <?= FORM::select('car_id', $cars, Arr::get($values, 'car_id')); ?>
        </div>
    </div>
</fieldset>
<?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn large success')); ?>
<?= FORM::close(); ?>