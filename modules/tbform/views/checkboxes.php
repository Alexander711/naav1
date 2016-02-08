<?php
defined('SYSPATH') or die('No direct script access.');
$available_errors = array_intersect_key($check_boxes, $errors);
$control_group_css = (!empty($available_errors)) ? 'control-group error' : 'control-group';
$checkbox_class = ($inline) ? 'checkbox inline' : 'checkbox';
$i = 0;
?>
<div class="<?= $control_group_css; ?>">
    <label class="control-label" <?php if ($horizontal) { echo 'style="text-align: left;"'; } ?>><?= $label; ?></label>
    <div class="controls" <?php if ($horizontal) { echo 'style="clear: both; margin-left: 0;"'; } ?>>
        <?php foreach ($check_boxes as $name => $value): ?>
            <?php $i++ ; ?>
            <label class="<?= $checkbox_class ?>">
                <?= FORM::checkbox($name, $value[$config['value_key']], (bool) Arr::get($values, $name, FALSE)).Arr::get($value, $config['label_key'], $config['default_label_text'].$i); ?>
            </label>
            <span class="help-block"><?= Arr::get($errors, $name); ?></span>
        <?php endforeach; ?>
    </div>
</div>