<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div id="tab">
    <ul>
        <li><?= HTML::anchor('#', 'Информация', array('class' => 'active')) ?></li>
        <li><?= HTML::anchor('cabinet/profile/edit_password', 'Смена пароля'); ?></li>
    </ul>
</div>
<?= FORM::open('cabinet/profile'); ?>
<div class="st_form">
    <ul>
        <li>
            <label class="lab">Имя пользователя</label>
            <?= FORM::input('username', Arr::get($values, 'username'), array('class' => 's_inp')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'username'); ?></div>
        </li>
        <li>
            <label class="lab">Email</label>
            <?= FORM::input('email', Arr::get($values, 'email'), array('class' => 's_inp')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'email'); ?></div>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit('submit', __('f_save'), array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>