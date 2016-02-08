<?php
defined('SYSPATH') or die('No direct script access.');
$file_input_attr = array();
if ($metro_stations_count < 1)
    $file_input_attr['disabled'] = 'disabled';
echo FORM::open(Request::current()->url(), array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'));
?>
<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label class="control-label" for="name">Название</label>
        <div class="controls">
            <?= FORM::input('name', Arr::get($values, 'name'), array('class' => 'span5')); ?>
            <span class="help-inline"><?= Arr::get($errors, 'name'); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="genitive_name">Название в родительном падеже </label>
        <div class="controls">
            <?= FORM::input('genitive_name', Arr::get($values, 'genitive_name'), array('class' => 'span5')); ?>
            <span class="help-inline"><?= Arr::get($errors, 'genitive_name'); ?></span>
            <p class="help-block">напр. Москвы, Санкт-Петербурга</p>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="davitus_name">Название в дательном падеже</label>
        <div class="controls">
            <?= FORM::input('dativus_name', Arr::get($values, 'dativus_name'), array('class' => 'span5')); ?>
            <span class="help-inline"><?= Arr::get($errors, 'davitus_name'); ?></span>
            <p class="help-block">напр. Москве, Санкт-Петербурге</p>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Изображение карты метро(Оригинал)</label>
        <div class="controls">
            <?= FORM::file('metro_map', $file_input_attr); ?>
            <span class="help-inline"><?= Arr::path($errors, '_external.metro_map'); ?></span>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Изображение карты метро(Чистовая)</label>
        <div class="controls">
            <?= FORM::file('metro_map_clear', $file_input_attr); ?>
            <span class="help-inline"><?= Arr::path($errors, '_external.metro_map_clear'); ?></span>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-large btn-success')) ?>
    </div>
</fieldset>