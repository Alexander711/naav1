<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open(Request::current()->uri(), array('class' => 'form-horizontal'));
?>
<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label class="control-label">Заголовок</label>
        <div class="controls">
            <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 'span5')); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Meta-description</label>
        <div class="controls">
            <?= FORM::textarea('meta_d', Arr::get($values, 'meta_d'), array('rows' => 2, 'class' => 'span5')); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Meta-keywords</label>
        <div class="controls">
            <?= FORM::textarea('meta_k', Arr::get($values, 'meta_k'), array('rows' => 2, 'class' => 'span5')); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Текст</label>
        <div class="controls">
            <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')); ?>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::submit(NULL, 'Отправить', array('class' => 'btn btn-large btn-success')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>
