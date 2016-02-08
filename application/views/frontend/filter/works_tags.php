<?php
defined('SYSPATH') or die('No direct script access.');
$div_attr['class'] = (count($category) < 2) ? 'filter_tags' : 'filter_tags columnize';
?>

<div <?= HTML::attributes($div_attr); ?>>
    <?php foreach ($category as $name => $value): ?>
        <p>
            <ul class="works_list">
                <li class="work-category-name"><?= $name; ?></li>
                <?php foreach ($value as $id => $work): ?>
                    <li><?= HTML::anchor('services/search/work_'.$id.$url, $work['name'], array('style' => 'line-height: 20px;')); ?>    </li>
                <?php endforeach; ?>
            </ul>
        </p>
    <?php endforeach; ?>
</div>