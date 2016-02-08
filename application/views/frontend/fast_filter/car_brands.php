<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="fast-filter-tags fast-filter-cars">
    <ul>
        <?php foreach ($car_brands as $id => $value): ?>
            <li><?= HTML::anchor('services/search/car_'.$id.'/city_'.$city_id, $value['name']); ?></li>
        <?php endforeach; ?>
    </ul>
    <div class="other-button">
        <?= HTML::anchor('filter/auto/city_'.$city_id, '<span>'.__('s_fast_filter_other_models').'</span>', array('class' => 'cars')) ?>
    </div>
</div>