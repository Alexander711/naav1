<?php
defined('SYSPATH') or die('No direct script access.');
$select_attrs['data-type'] = 'filter_auto';
?>
<?= Message::render(); ?>
<div class="row">
    <?= $content->text; ?>
</div>
<?= View::factory('frontend/filter/navigation')
        ->set('current_filter', 'auto')
        ->set('selected_city_ids', $selected_city_ids)
        ->render(); ?>
<?= View::factory('frontend/filter/cities_navigation')->set('url', 'filter/auto')->set('cities', $cities)->set('city_id', $selected_city_ids['has_cars'])->render(); ?>


<?php if (!empty($metro) OR !empty($districts)): ?>
<?php
// Генерация селектов округов и станций метро
echo View::factory('frontend/navigation/districts_metro')
         ->set('districts', $districts)
         ->set('metro_stations', $metro_stations)
         ->set('city_id', $selected_city_ids['has_cars'])
         ->set('selected', NULL)
         ->set('select_attrs', $select_attrs)
         ->render();
?>
<?php endif; ?>
<?= View::factory('frontend/filter/cars_tags')
        ->set('car_brands', $car_brands)
        ->set('url', '/city_'.$selected_city_ids['has_cars']);
?>


