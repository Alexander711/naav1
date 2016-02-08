<?php
defined('SYSPATH') or die('No direct script access.');
$attr['style'] = (isset($work_id)) ? 'display: none' : '';
?>
<div class="search_sort_list works_list" <?= HTML::attributes($attr); ?>>
    <div class="title">Сортировка по услугам</div>
    <?php foreach ($works as $category_name => $work): ?>
        <?php
        $category_attr = array('class' => 'category');
        $list_attr = NULL;
        $icon_attr['class'] = 'icon icon-expand';
        if (isset($work_id) AND Arr::get($work, $work_id))
        {
            $category_attr['class'] = 'category active';
            $list_attr['style'] = 'display: block;';
            $icon_attr['class'] = 'icon icon-close';
        }
        ?>
        <div <?= HTML::attributes($category_attr); ?>>
            <div class="title" style="position: relative;"><?= $category_name; ?><div <?= HTML::attributes($icon_attr) ?>></div></div>
            <div class="list" <?= HTML::attributes($list_attr); ?>>
                <ul>
                    <?php foreach ($work as $id => $value): ?>
                        <?php $checked = (isset($work_id) AND $work_id == $id) ? TRUE : FALSE; ?>
                        <li><label <?php if ($checked) echo 'class="active"'; ?>><?= FORM::checkbox('work[]', $id, $checked).$value['name']; ?></label></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
</div>
