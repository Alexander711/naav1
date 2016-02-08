<?php
defined('SYSPATH') or die('No direct script access.');
$div_attr['class'] = (count($car_brands) < 5) ? 'filter_tags' : 'filter_tags columnize';
?>
<div <?= HTML::attributes($div_attr); ?>>
    <?php foreach ($car_brands as $id => $value): ?>
        <div class="car-brand-item">
            <div class="car-brand-logo">
                <?php if (trim($value['thumb'])): ?>
                    <?= HTML::image($value['thumb']) ?>
                    <div class="system"></div>
                <?php endif; ?>
            </div>
            <div class="car-brand-name">
                <?= HTML::anchor('services/search/car_'.$id.$url, __('filter_tag_car', array(':full_name' => $value['full_name']))); ?>
                <div class="system"></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
