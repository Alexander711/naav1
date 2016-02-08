<?php
defined('SYSPATH') or die('No direct script access.');
$attr = array(
    'mail_from' => array(
        'class' => 'control-group'
    ),
    'mail_to' => array(
        'class' => 'control-group'
    ),
    'text' => array(
        'class' => 'control-group'
    ),
    'title' => array(
        'class' => 'control-group'
    ),
);
foreach ($errors as $name => $e)
{
    $attr[$name]['class'] .= ' error';
}
echo FORM::open(NULL, array('class' => 'form-horizontal'));
?>
<fieldset>
    <legend>Отправка письма</legend>
    <div <?= HTML::attributes($attr['mail_from']); ?>>
        <label class="control-label">От</label>
        <div class="controls">
            <?= FORM::select('mail_from', $mail_from_addresses, Arr::get($values, 'mail_from')); ?>
            <p class="help-block"><?= Arr::get($errors, 'mail_from'); ?></p>
        </div>
    </div>
    <div <?= HTML::attributes($attr['mail_to']); ?>>
        <label class="control-label">Кому</label>
        <div class="controls">
            <?= FORM::input('mail_to', Arr::get($values, 'mail_to'), array('class' => 'span3')); ?>
            <p class="help-block"><?= Arr::get($errors, 'mail_to'); ?></p>
        </div>
    </div>
    <div <?= HTML::attributes($attr['title']); ?>>
        <label class="control-label">Заголовок</label>
        <div class="controls">
            <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 'span6')); ?>
            <p class="help-block"><?= Arr::get($errors, 'title'); ?></p>
        </div>
    </div>
    <div <?= HTML::attributes($attr['text']); ?>>
        <label class="control-label">Текст</label>
        <div class="controls">
            <p class="help-block"><?= Arr::get($errors, 'text'); ?></p>
            <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')) ?>
        </div>
    </div>
    <div class="form-actions">
        <?= FORM::submit(NULL, 'Отправить', array('class' => 'btn btn-large btn-primary')) ?>
    </div>
</fieldset>
<?= FORM::close(); ?>