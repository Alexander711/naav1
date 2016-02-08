<?php
defined('SYSPATH') or die('No direct script access.');
echo Message::render();
echo FORM::open();
?>
<h1>Активация аккаунта</h1>

<strong>На ваш емейл были отправлены инструкции по активации аккаунта. Пожалуйста, следуйсте им.</strong>
<br>
<br>

<h4>Повторно выслать инструкции по активации аккаунта</h4>
<div class="st_form">
    <ul>
        <li>
            <label class="lab">Введите ваш емейл</label>
            <?= Form::input('email', Arr::get($values, 'email', ""), array('class' => 's_inp', 'id' => 'email',)); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'email'); ?></div>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit('submit', "Отправить", array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>