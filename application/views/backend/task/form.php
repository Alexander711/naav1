<?php
defined('SYSPATH') or die('No direct script access.');
$title_error_css = (Arr::get($errors, 'title')) ? 'error' : '';
$priority_error_css = (Arr::get($errors, 'priority')) ? 'error' : '';
$status_error_css = (Arr::get($errors, 'status')) ? 'error' : '';
$text_error_css = (Arr::get($errors, 'text')) ? 'error' : '';

echo FORM::open(NULL, array('class' => 'form-horizontal'));
?>
<fieldset>
    <legend>Параметры задачи</legend>
    <div class="control-group <?= $title_error_css ?>">
        <label class="control-label">Заголовок</label>
        <div class="controls">
            <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 'span5')) ?>
            <span class="help-inline"><?= Arr::get($errors, 'title'); ?></span>
        </div>
    </div>
    <div class="control-group <?= $priority_error_css; ?>">
        <label class="control-label">Приоритет</label>
        <div class="controls">
            <?php foreach ($priorities as $id => $value): ?>
            <?php $checked = (Arr::get($values, 'priority', 2) == $id) ? TRUE : FALSE; ?>
                <label class="radio">
                    <?= FORM::radio('priority', $id, $checked).__($value['i18n']); ?>
                </label>
            <?php endforeach; ?>
            <span class="help-block"><?= Arr::get($errors, 'priority'); ?></span>
        </div>
    </div>
    <div class="control-group <?= $status_error_css; ?>">
        <label class="control-label">Статус задачи</label>
        <div class="controls">
            <?php foreach ($statuses as $id => $value): ?>
            <?php $checked = (Arr::get($values, 'status', 1) == $id) ? TRUE : FALSE; ?>
                <label class="radio">
                    <?= FORM::radio('status', $id, $checked).'<span class="'.$value['css'].'">'.__($value['i18n']).'</span>'; ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="control-group <?= $text_error_css; ?>">
        <label class="control-label">Текст</label>
        <div class="controls">
             <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text', array('style' => 'width: 300px;'))); ?>
            <span class="help-block"><?= Arr::get($errors, 'text'); ?></span>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::button(NULL, 'Отправить', array('class' => 'btn btn-large btn-success', 'type' => 'submit')); ?>
        <?= FORM::button(NULL, 'Очистить', array('class' => 'btn btn-large', 'type' => 'reset')); ?>
    </div>
</fieldset>