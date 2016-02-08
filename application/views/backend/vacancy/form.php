<?php
defined('SYSPATH') or die('No direct script access.');
$checkboxes = array(
    'active' => array(
        'val' => 1,
        'label' => 'Активен'
    )
);
echo TbForm::open($url, array('class' => 'form-horizontal'), $values, $errors);
?>
<fieldset>
    <legend>Параметры вакансии</legend>
    <?= TbForm::input('title', array('label' => 'Заголовок')); ?>
    <?= TbForm::checkboxes($checkboxes, 'Статус'); ?>
</fieldset>
<h3>Текст вакансии</h3>
<?= TbForm::ckeditor('text', array('label' => '')); ?>
<?= TbForm::actions('save', array('horizontal' => FALSE)); ?>
<?= FORM::close(); ?>

