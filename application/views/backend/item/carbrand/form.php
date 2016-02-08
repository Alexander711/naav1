<?php
defined('SYSPATH') or die('No direct script access.');
echo Message::show_errors($errors);
echo FORM::open($url, array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'));
?>

<fieldset>
    <legend>Параметры марки автомобиля</legend>
    <div class="control-group">
        <label for="name" class="control-label">Название (eng)</label>
        <div class="controls">
            <?= FORM::input('name', Arr::get($values, 'name'), array('id' => 'name', 'class' => 'span5')); ?>
        </div>
    </div>
    <div class="control-group">
        <label for="name_ru" class="control-label">Название (rus)</label>
        <div class="controls">
            <?= FORM::input('name_ru', Arr::get($values, 'name_ru'), array('id' => 'name_ru', 'class' => 'span5')); ?>
        </div>
    </div>
    <div class="control-group">
        <label for="img-path" class="control-label">Логотип</label>
        <div class="controls">
            <?= FORM::file('img_path'); ?>
            <?php if (file_exists($car->img_path)): ?>
                <p class="help-block">
                    <?= HTML::image($car->img_path, array('height' => 100)) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>
    <div class="control-group">
        <label for="thumb-img-path">Логотип (миниатюра)</label>
        <div class="controls">
            <?= FORM::file('thumb_img_path'); ?>
            <?php if (file_exists($car->thumb_img_path)): ?>
                <p class="help-block">
                    <?= HTML::image($car->thumb_img_path) ?>
                </p>
            <?php endif; ?>
        </div>


    </div>
    <div class="form-actions">
        <?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-large btn-success')); ?>
        <?= FORM::submit('edit_content', 'Сохранить и перейти к редактированию страницы', array('class' => 'btn btn-large btn-primary')) ?>
    </div>
</fieldset>

<?= FORM::close(); ?>