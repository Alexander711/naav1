<?php
defined('SYSPATH') or die('No direct script access.');
$control_group_attr['class'] = (!empty($errors)) ? 'control-group error' : 'control-group';
$radio_label_attr['class'] = (Arr::get($options, 'inline')) ? 'radio inline' : 'radio';
?>
<div <?= HTML::attributes($control_group_attr); ?>>
    <label class="control-label"><?= Arr::get($options, 'label', $name); ?></label>
    <div class="controls">
        <?php foreach ($buttons as $value => $text): ?>
            <label <?= HTML::attributes($radio_label_attr); ?>>
                <?php $checked = (Arr::get($values, $name) == $value) ? TRUE : FALSE; ?>
                <?= FORM::radio($name, $value, $checked).$text; ?>
            </label>
        <?php endforeach; ?>
        <?php if (Arr::get($options, 'help')): ?>
            <p class="help-block" style="color: #999999;"><?= $options['help']; ?></p>
        <?php endif; ?>
        <?php foreach ($errors as $error): ?>
            <p class="help-block"><?= $error; ?>/p>
        <?php endforeach; ?>
    </div>
</div>