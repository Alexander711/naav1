<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="clearfix">
    <ul class="nav nav-tabs">
        <?php foreach ($cities->find_all() as $city): ?>
            <?php $attr = ($city_id == $city->id) ? array('class' => 'active') : NULL; ?>
            <li <?= HTML::attributes($attr); ?>><?= HTML::anchor('admin/item/metro/index/'.$city->id, $city->name); ?></li>
        <?php endforeach; ?>
    </ul>
</div>