<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open('forgot_password');
?>
<div class="st_form">
    <ul>
        <li>
            <label class="lab" for="username_email" style="width: 195px;">Имя пользователя или пароль</label>
            <?= FORM::input('username_email', Arr::get($values, 'username_email')); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'username_email'); ?></div>
        </li>
        <li>
            <label class="lab" style="width: 195px;">&nbsp;</label>
            <?= FORM::submit('submit', 'Восстановить', array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>