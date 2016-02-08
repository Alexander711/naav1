<?php
defined('SYSPATH') or die('No direct script access.');
$cities = array('0' => 'Все города') + $cities;
?>
<?= FORM::open('reviews/add_review'); ?>
<div class="st_form">
    <ul>
        <li>
            <label class="lab">Город</label>
            <?= FORM::select('city_id', $cities, Arr::get($values, 'city_id'), array('class' => 'add_review_city')); ?>
        </li>
        <li >
            <label class="lab">Сервис</label>
            <?= FORM::select('service_id', $services, Arr::get($values, 'service_id'), array('class' => 'add_review_services')); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'service_id'); ?></div>
        </li>
        <li>
            <label class="lab">Имя</label>
            <?= FORM::input('name', Arr::get($values, 'name'), array('class' => 's_inp')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'name'); ?></div>
        </li>
        <li>
            <label class="lab">Email</label>
            <?= FORM::input('email', Arr::get($values, 'email'), array('class' => 's_inp')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'email'); ?></div>
        </li>
        <li>
            <label class="lab">Текст отзыва</label>
            <?= FORM::textarea('text', Arr::get($values, 'text'), array('class' => 's_tarea')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'text'); ?></div>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <div style="margin-left: 170px;">
                <?= __('add_review_rule'); ?>
            </div>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit(NULL, __('f_send'), array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>