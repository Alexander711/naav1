<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/user_edit')
         ->set('current_url', Request::current()->url())
         ->set('user_id', $user_id)
         ->render();
?>
<?= FORM::open(Request::current()->uri(), array('autocomplete' => 'off', 'class' => 'form-horizontal')) ?>
<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label class="control-label">Пароль</label>
        <div class="controls">
            <?= Form::password('password', NULL, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?>

            <label><?= Form::checkbox(NULL, NULL, FALSE, array('id' => 'reg_pass_view', )); ?> Показать пароль</label>
            <p class="help-block" style="color: red;"><?= Arr::path($errors, '_external.password'); ?></p>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-success btn-large')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>
