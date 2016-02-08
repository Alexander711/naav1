<?php
defined('SYSPATH') or die('No direct script access.');
$metro_district_attr = (empty($districts) AND empty($metro_stations)) ? array('style' => 'display: none') : NULL;
$selected = Arr::extract($selected, array('metro_id', 'district_id'));
?>
<div class="metro_district" <?= HTML::attributes($metro_district_attr); ?>>

    <?php if (!empty($districts)): ?>
        Округ:
        <select name="district" data-city="<?= $city_id; ?>" <?= HTML::attributes($select_attrs); ?>>
            <?= View::factory('frontend/navigation/districts_form_select')->set('districts', $districts)->set('district_id', $selected['district_id'])->render(); ?>
        </select>
    <?php endif; ?>

    <?php if (!empty($metro_stations)): ?>
        Метро:
        <select name="metro" data-city="<?= $city_id; ?>" <?= HTML::attributes($select_attrs); ?>>
            <?= View::factory('frontend/navigation/metro_stations_form_select')->set('metro_stations', $metro_stations)->set('metro_id', $selected['metro_id'])->render(); ?>
        </select>
    <?php endif; ?>
</div>

