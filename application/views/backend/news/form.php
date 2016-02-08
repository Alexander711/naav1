<?php
defined('SYSPATH') or die('No direct script access.');
$company_name = Arr::get($values, 'company_name');
$checkboxes = array(
    'active' => array(
        'val' => 1,
        'label' => 'Активен'
    )
);
echo TbForm::open(NULL,array('class' => 'form-horizontal'), $values, $errors);
?>
<fieldset>
    <legend>Параметры страницы</legend>
    <?= TbForm::input('title', array('label' => 'Заголовок')); ?>
    <?= TbForm::checkboxes($checkboxes, 'Статус страницы'); ?>
    <?php if ($company_name): ?>
        <div class="control-group">
            <label class="control-label" style="margin-top: 0;"></label>
            <div class="controls"><?= $company_name ?></div>
        </div>
    <?php endif; ?>

</fieldset>
<h3>Текст</h3>
<?= TbForm::ckeditor('text', array('label' => '')) ?>
<?= TbForm::actions('save_and_reset', array('horizontal' => FALSE)); ?>
<?= FORM::close(); ?>
