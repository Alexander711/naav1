<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open($url, array('autocomplete' => 'off'));
?>
<fieldset>
    <legend>Параметры пользователя</legend>
    <table>
        <tr>
            <td style="width: 200px;"><?= __('f_username'); ?></td>
            <td><?= FORM::input('username', Arr::get($values, 'username')); ?></td>
            <td><?= Message::show_once_error($errors, 'username'); ?></td>
        </tr>
        <tr>
            <td><?= __('f_password'); ?></td>
            <td>
                <?= Form::password('password', NULL, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?>
                <br />
                <?= Form::checkbox(NULL, NULL, FALSE, array('id' => 'reg_pass_view', )); ?>Показать пароль
            </td>
            <td><?= Message::show_once_error($errors, 'password').Message::show_once_error(Arr::get($errors, '_external', array()), 'password'); ?></td>
        </tr>
        <tr>
            <td><?= __('f_email'); ?></td>
            <td><?= FORM::input('email', Arr::get($values, 'email')); ?></td>
            <td><?= Message::show_once_error($errors, 'email'); ?></td>
        </tr>
    </table>
</fieldset>
<?= FORM::submit(NULL, 'Сохранить'); ?>
<?= FORM::close(); ?>