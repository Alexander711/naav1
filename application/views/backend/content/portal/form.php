<?php
defined('SYSPATH') or die('No direct script access.');
echo TbForm::open($url, array('class' => 'form-horizontal'), $values, $errors);
$active_checkbox = array(
    'active' => array(
        'val' => 1,
        'label' => 'Активен'
    ),
);
?>
<div class="row-fluid">
    <div class="span6">
        <fieldset style="margin-bottom: 0;">
            <legend>Параметры страницы</legend>
            <?= TbForm::input('title', array('label' => 'Заголовок')); ?>
            <?= TbForm::input('url', array('label' => 'URL')); ?>
            <?= TbForm::checkboxes($active_checkbox, 'Статус страницы'); ?>
        </fieldset>
    </div>
    <div class="span6">
        <fieldset style="margin-bottom: 0;">
            <legend>Мета-теги</legend>
            <?= TbForm::textarea('keywords', array('label' => 'Ключевые слова (meta-keywords)')); ?>
            <?= TbForm::textarea('description', array('label' => 'Описание (meta-description)')); ?>
        </fieldset>
    </div>
</div>
<div class="row-fluid">
    <?= TbForm::ckeditor('text', array('label' => 'Текст')); ?>
    <?= TbForm::actions('save_and_reset', array('horizontal' => FALSE)); ?>
</div>
<?= FORM::close(); ?>

