<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open('activate_password?key='.$key);
?>
<div class="st_form">
    <ul>
        <li>
            <label class="lab">Введите новый пароль</label>
            <?= Form::password('password', NULL, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?>
            <?= Form::checkbox(NULL, NULL, FALSE, array('id' => 'reg_pass_view', )); ?><label for="reg_pass_view">Показать пароль</label>
            <div class="form_error"><?= Message::show_once_error($errors, 'password'); ?></div>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit('submit', __('f_save'), array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>