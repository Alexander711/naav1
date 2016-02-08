<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="search_sort_list discounts_list">
    <div class="title">Сортировка по скидкам</div>
    <div class="list">
        <ul>
            <?php foreach ($discounts as $id => $percent): ?>
            <?php
            if (isset($selected_discounts) AND in_array($id, $selected_discounts))
            {
                $label_attr['class'] = 'active';
                $checked = TRUE;
            }
            else
            {
                $label_attr = array();
                $checked = FALSE;
            }
            ?>
                <li><label <?= HTML::attributes($label_attr); ?>><?= FORM::checkbox('discount[]', $id, $checked, array('autocomplete' => 'off')).'Сервисы со скидкой '.$percent.'%'; ?></label></li>
            <?php endforeach ;?>
        </ul>
    </div>
</div>