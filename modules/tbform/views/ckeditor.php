<?php
defined('SYSPATH') or die('No direct script access.');
$control_group_attr['class'] = (!empty($errors)) ? 'control-group error' : 'control-group';
$label_attr = array();
$controls_attr = array();
if (!Arr::get($options, 'horizontal', FALSE))
{
    $label_attr['style'] = 'text-align: left;';
    $controls_attr['style'] = 'clear: both; margin-left: 0;';
}
?>
<div <?= HTML::attributes($control_group_attr); ?>>
    <label class="control-label" <?= HTML::attributes($label_attr); ?>><?= Arr::get($options, 'label', $name); ?></label>
    <div class="controls" <?= HTML::attributes($controls_attr); ?>>
        <?= $form_element; ?>
        <?php if (Arr::get($options, 'help')): ?>
            <p class="help-block" style="color: #999999;"><?= $options['help'] ?></p>
        <?php endif; ?>
        <?php foreach ($errors as $error): ?>
            <p class="help-block"><?= $error; ?></p>
        <?php endforeach; ?>
    </div>
</div>
