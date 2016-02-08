<?php
defined('SYSPATH') or die('No direct script access.'); ?>
    <?= $reg_steps; ?>
<?= FORM::open('registration'); ?>
    <div class="st_form">
        <ul>
            <li>
                <label class="lab" for="username"><?= __('f_username'); ?></label>
                <?= Form::input('username', Arr::get($values, 'username'), array('class' => 's_inp', 'id' => 'username')); ?>
                <div class="form_error"><?= Message::show_once_error($errors, 'username'); ?></div>
            </li>
            <li>
                <label class="lab"><?= __('f_password'); ?></label>
                <?= Form::password('password', NULL, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?>
                <?= Form::checkbox(NULL, NULL, FALSE, array('id' => 'reg_pass_view', )); ?><label for="reg_pass_view">Показать пароль</label>
                <div class="form_error"><?= Message::show_once_error($errors, 'password').Message::show_once_error(Arr::get($errors, '_external', array()), 'password'); ?></div>
            </li>
            <li>
                <label class="lab" for="email"><?= __('f_email'); ?></label>
                <?= Form::input('email',  Arr::get($values, 'email'), array('class' => 's_inp', 'id' => 'email')); ?>
                <div class="form_error"><?= Message::show_once_error($errors, 'email'); ?></div>
            </li>
            <li>
                <label class="lab">&nbsp;</label>
                <textarea rows="7" cols="75" readonly="readonly">
                    <?= strip_tags(DB::select('text')->from('content_site')->where('url', '=', 'ustav')->execute()->get('text')); ?>
                </textarea>
                <br />
                <div style="margin-left: 170px">
                    <?= FORM::checkbox('accept_rule', 1, FALSE).__('f_accept_rule'); ?>
                </div>
                <div class="form_error"><?= Message::show_once_error(Arr::get($errors, '_external', array()), 'accept_rule'); ?></div>
            </li>
            <li>
                <label class="lab">&nbsp;</label>
                <?= FORM::submit('submit', __('f_continue'), array('class' => 's_button')); ?>
            </li>
        </ul>
    </div>
<?= FORM::close(); ?>

