<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="title">Марки автомобилей</div>
<?php if (count($auto_brands) > 0): ?>
    <ul>
        <?php foreach ($auto_brands as $id => $name): ?>
            <li><?= HTML::anchor('services/search/car_'.$id.'/city_'.$city_id, $name); ?></li>
        <?php endforeach; ?>

    </ul>
    <div class="other"><?= HTML::anchor('filter/auto/city_'.$city_id, '<span>'.__('s_fast_filter_other_models').'</span>', array('class' => 'car')); ?></div>
<?php else: ?>
    <p><?= __('s_fast_filter_empty_models'); ?></p>
<?php endif; ?>
<div class="clear"></div>