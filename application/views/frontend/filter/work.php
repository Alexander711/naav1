<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<?= Message::render(); ?>
<div class="row">
    <?= $content->text; ?>
</div>
<?= View::factory('frontend/filter/navigation')->set('current_url', 'filter/work')->render(); ?>
<?= View::factory('frontend/filter/cities_navigation')->set('url', 'filter/work')->set('cities', $cities)->set('city_id', $city_id)->render(); ?>
<?=
    View::factory('frontend/navigation/districts_metro')
         ->set('districts', $districts)
         ->set('metro', $metro_stations)
         ->set('district_id', $district_id)
         ->set('metro_id', $metro_id)
         ->set('url_pies', $url_pies)
         ->set('url', 'filter/work')
         ->render();
?>
<?php
$div_attr['class'] = 'filter_tags columnize';
if (count($category) < 2)
    $div_attr['class'] = 'filter_tags';
?>
<div <?= HTML::attributes($div_attr); ?>>
    <?php foreach ($category as $name => $value): ?>
        <p>

            <ul class="works_list">
                <li class="work-category-name"><?= $name; ?></li>
                <?php foreach ($value as $id => $work): ?>
                    <li><?= HTML::anchor('services/search/work_'.$id.'/'.implode('/', $url_pies), $work['name'], array('style' => 'line-height: 20px;')); ?>    </li>
                <?php endforeach; ?>
            </ul>
        </p>
    <?php endforeach; ?>
</div>


