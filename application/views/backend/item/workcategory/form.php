<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open(Request::current()->url(), array('class' => 'form-horizontal'));
?>
<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label for="name" class="control-label">Название категории</label>
        <div class="controls">
            <?= FORM::input('name', Arr::get($values, 'name'), array('class' => 'span5')); ?>
            <span class="help-inline"><?= Arr::get($errors, 'name'); ?></span>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-large btn-success')); ?>
        <?= FORM::submit('add_work', 'Сохранить и добавить услугу в категорию', array('class' => 'btn btn-large btn-primary')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>