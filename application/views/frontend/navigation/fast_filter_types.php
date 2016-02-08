<?php
defined('SYSPATH') or die('No direct script access.');
$attr['data-action'] = 'choose-type';
?>
<div class="filter-type-nav">
    <ul>
        <li class="title"></li>
        <?php foreach ($types as $type): ?>
        <?php
        if ($type == $current_type)
            $attr['class'] = 'active';
        else
            unset($attr['class']);
        ?>
            <li <?= HTML::attributes($attr); ?> rel="<?= $type; ?>"><?= __('fast_filter_'.$type); ?></li>
        <?php endforeach; ?>
    </ul>

</div>