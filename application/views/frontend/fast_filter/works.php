<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="fast-filter-tags fast-filter-works">
    <ul>
        <?php foreach($works as $id => $name): ?>
        <li><?= HTML::anchor('services/search/work_'.$id.'/city_'.$city_id, $name); ?></li>
        <?php endforeach; ?>        
    </ul>
    <div class="other-button">
        <?= HTML::anchor('filter/work/city_'.$city_id, '<span>'.__('s_fast_filter_other_works').'</span>', array('class' => 'works')) ?>
    </div>
</div>