<?php
defined('SYSPATH') or die('No direct script access.');
$select_attrs['data-type'] = 'filter_work';
?>
<?= Message::render(); ?>
<div class="row">
    <?= $content->text; ?>
</div>
<?= View::factory('frontend/filter/navigation')->set('current_filter', 'work')->set('selected_city_ids', $selected_city_ids)->render(); ?>
<?= View::factory('frontend/filter/cities_navigation')->set('url', 'filter/work')->set('cities', $cities)->set('city_id', $selected_city_ids['has_works'])->render(); ?>
<?=
    View::factory('frontend/navigation/districts_metro')
         ->set('city_id', $selected_city_ids['has_works'])
         ->set('selected', NULL)
         ->set('districts', $districts)
         ->set('metro_stations', $metro_stations)
         ->set('select_attrs', $select_attrs)
         ->render();
?>
<?= View::factory('frontend/filter/works_tags')
        ->set('category', $category)
        ->set('url', '/city_'.$selected_city_ids['has_works']);
?>



