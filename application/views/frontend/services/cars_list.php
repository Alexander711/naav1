<?php
defined('SYSPATH') or die('No direct script access.');
$attr['style'] = (isset($car_id) AND array_key_exists($car_id, $cars)) ? 'display: none' : '';
?>
<div class="search_sort_list cars_list" <?= HTML::attributes($attr); ?>>
    <div class="title">Сортировка по маркам авто</div>
    <div class="list">
        <ul>
            <?php foreach ($cars as $id => $value): ?>
                <?php $checked = (isset($car_id) AND $car_id == $id) ? TRUE : FALSE; ?>
                <?php if (!trim($value['name'])) $value['name'] = $value['name_ru']; ?>
                <li><label <?php if ($checked) echo 'class="active"'; ?>><?= FORM::checkbox('car[]', $id, $checked).$value['name']; ?></label></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>