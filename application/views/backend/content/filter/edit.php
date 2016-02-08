<?php
defined('SYSPATH') or die('No direct script access.');
echo Debug::vars($errors);
echo FORM::open($url); ?>
<fieldset>
    <legend>Параметры страницы</legend>
    <div class="clearfix">
        <label>Город</label>
        <div class="input">
            <span class="uneditable-input"><?= $city; ?></span>
        </div>
    </div>
    <div class="clearfix">
        <label>Тип:</label>
        <div class="input">
            <span class="uneditable-input"> <?= $type; ?></span>
        </div>
    </div>
</fieldset>
<h3>Текст</h3>
<div class="clearfix">
    <?= FORM::textarea('text', $values['text'], array('id' => 'text')); ?>
</div>
<div class="clearfix">
    <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn large success')); ?>
</div>
<?= FORM::close(); ?>