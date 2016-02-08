<?php
defined('SYSPATH') or die('No direct script access.');
$form_actions_attr = array();
if (!Arr::get($options, 'horizontal', TRUE))
    $form_actions_attr['style'] = 'padding-left: 0;';
?>
<div class="form-actions" <?= HTML::attributes($form_actions_attr); ?>>
    <?php foreach ($actions as $type => $params): ?>
        <?= FORM::button(Arr::get($params, 'name'), __($params['text']), array('type' => $type) + Arr::get($params, 'attributes', array())); ?>
    <?php endforeach; ?>
</div>