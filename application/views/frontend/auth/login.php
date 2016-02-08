<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<div class="login_form">
<?= Message::render(); ?>
<?= FORM::open('login'); ?>
    <table>
        <tr>
            <td><?= __('f_username'); ?></td>
            <td><?= FORM::input('username', Arr::get($values, 'username'), array('class' => 's_inp')); ?></td>
        </tr>
        <tr>
            <td><?= __('f_password'); ?></td>
            <td><?= FORM::password('password', NULL, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?><?= Form::checkbox(NULL, NULL, FALSE, array('id' => 'reg_pass_view', )); ?>Показать пароль</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?= HTML::anchor('forgot_password', 'Забыл пароль'); ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><?= FORM::submit(NULL, __('butt_login')) ?></td>
        </tr>
    </table>
<?= FORM::close(); ?>
</div>
