<?php
defined('SYSPATH') or die('No direct script access.');
$company_name = Arr::get($values, 'company_name');
echo Message::show_errors($errors);
echo FORM::open($url);
?>
<fieldset style="width: 450px">
    <legend>Параметры страницы</legend>
    <p>Заголовок</p>
    <?= FORM::input('title', Arr::get($values, 'title')); ?>
    <p>Статус страницы</p>
    <?= FORM::checkbox('active', 1, (bool) Arr::get($values, 'active', FALSE)); ?>
    <?php if ($company_name): ?>
        <p>Компания: <?= $company_name ?></p>
    <?php endif; ?>

</fieldset>

<fieldset>
    <legend>Текст</legend>
    <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')); ?>
</fieldset>
<?= FORM::submit(NULL, 'Сохранить', array('class' => 'submit')); ?>
<?= FORM::close(); ?>