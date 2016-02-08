<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="fast-filter-tags fast-filter-districts">
    <ul>
        <?php foreach($districts as $id => $district): ?>
        <li><?= HTML::anchor('services/search/district_'.$id, __('fast_filter_district_tag', array(':name' => $district->name))); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="other-button">
        <?= HTML::anchor('filter/district/city_'.$city_id, '<span>'.__('s_fast_filter_other_districts').'</span>', array('class' => 'districts')) ?>
    </div>
</div>