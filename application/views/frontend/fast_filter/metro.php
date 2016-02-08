<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="fast-filter-tags fast-filter-metro">
    <ul>
        <?php foreach($metro_stations as $id => $metro): ?>
        <li><?= HTML::anchor('services/search/metro_'.$id, __('fast_filter_metro_tag', array(':name' => $metro->name))); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="other-button">
        <?= HTML::anchor('filter/metro/city_'.$city_id, '<span>'.__('s_fast_filter_other_metro').'</span>', array('class' => 'metro')) ?>
    </div>
</div>