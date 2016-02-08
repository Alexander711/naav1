<?php
defined('SYSPATH') or die('No direct script access.');
$control_group_css_class = (!empty($errors))
                         ? 'control-group error'
                         : 'control-group';
?>
<div class="<?= $control_group_css_class; ?>">
    <label for="<?= $label['for']; ?>" <?php if ($horizontal) { echo 'style="text-align: left;"'; } ?>><?= $label['text']; ?></label>
    <div class="controls" <?php if ($horizontal) { echo 'style="clear: both; margin-left: 0;"'; } ?>>
        <?= $form_element; ?>
        <?php if ($help): ?>
            <p class="help-block" style="color: #999999"><?= $help; ?></p>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <p class="help-block"><?= $error; ?></p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>