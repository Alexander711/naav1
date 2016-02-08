<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div id="tab">
    <ul>
        <li><?= HTML::anchor('cabinet/profile', 'Информация') ?></li>
        <li><?= HTML::anchor('cabinet/profile/edit_password', 'Смена пароля', array('class' => 'active')); ?></li>
    </ul>
</div>
<?= FORM::open('cabinet/profile/edit_password'); ?>
<div class="st_form">
    <ul>
        <li>
            <label class="lab">Новый пароль</label>
            <?= Form::password('password', NULL, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?>
            <?= Form::checkbox(NULL, NULL, FALSE, array('id' => 'reg_pass_view', )); ?><label for="reg_pass_view">Показать пароль</label>
            <div class="form_error"><?= Message::show_once_error($errors, 'password').Message::show_once_error(Arr::get($errors, '_external', array()), 'password'); ?></div>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit('submit', __('f_save'), array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>