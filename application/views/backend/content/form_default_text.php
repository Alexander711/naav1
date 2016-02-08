<?php
defined('SYSPATH') or die('No direct script access.');
echo FORM::open(Request::current()->uri(), array('class' => 'form-horizontal'));
?>
<fieldset>
    <legend><?= $title; ?></legend>
    <div class="control-group">
        <label class="control-label">Текст</label>
        <div class="controls">
            <?= FORM::textarea('text', $values['text'], array('id' => 'text')); ?>
        </div>
    </div>
    <div class="form-actions">
        <p class="help-block"><?= Arr::get($errors, 'text'); ?></p>
        <?= FORM::submit(NULL, 'Отправить', array('class' => 'btn btn-large btn-success')); ?>
    </div>
</fieldset>
<?= FORM::close(); ?>
