<?php
defined('SYSPATH') or die('No direct script access.');
$text_error_css = (Arr::get($errors, 'text')) ? 'error' : '';
?>
<div class="row-fluid">
    <div class="span6">
        <h3><?= $task->title; ?></h3>
    </div>
    <div class="span6" style="text-align: right;">
        <?= MyDate::show($task->date_create, TRUE); ?>
    </div>
    <div class="clearfix">
        <hr style="clear: both;"/>
        <?= $task->text; ?>
    </div>
</div>
<div class="row-fluid">
    <h5>Дополнений: <?= count($task->disputes->find_all()); ?></h5>
    <?php foreach ($task->disputes->find_all() as $d): ?>
        <div class="row-fluid">
            <div style="text-align: right;"><?= MyDate::show($d->date_create, TRUE).' '.HTML::delete_button($d->id, 'admin/development/dispute'); ?></div>
            <div><?= $d->text; ?></div>
        </div>
        <hr />
    <?php endforeach; ?>

    <?= FORM::open(NULL, array('class' => 'well')); ?>
    <h3>Добавление дополнения</h3>
    <div class="control-group <?= $text_error_css; ?>">
        <label>Текст</label>
        <div class="controls">
            <?= FORM::textarea('text', Arr::get($values, 'text'), array('class' => 'span8', 'rows' => 4)); ?>
        </div>
        <span class="help-block"><?= Arr::get($errors, 'text'); ?></span>
    </div>

    <?= FORM::submit(NULL, 'Отправить', array('class' => 'btn btn-success')); ?>

    <?= FORM::close(); ?>
</div>
