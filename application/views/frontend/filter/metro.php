<?php
defined('SYSPATH') or die('No direct script access.');
$attr = array();
?>
<div class="filter_navigation">
    Подбор сервиса по: <?= HTML::anchor('filter/auto', 'Марке автомобиля').HTML::anchor('filter/work', 'Услуге').HTML::anchor('filter/metro', 'Станции метро', array('class' => 'active')).HTML::anchor('filter/district', 'Округу'); ?>
</div>
<div class="cities_navigation" style="clear: both;">
    <ul>
        <?php foreach ($cities as $id => $name): ?>
            <?php
            $attr['class'] = ($id == $city_id) ? 'active' : '';
            ?>
            <li><?= HTML::anchor('filter/metro/city_'.$id, $name, $attr); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
$div_attr['class'] = 'filter_tags columnize';
if (count($metro_stations) < 5)
    $div_attr['class'] = 'filter_tags';
?>
<div <?= HTML::attributes($div_attr); ?>>
    <?php foreach ($metro_stations as $metro_id => $metro_name): ?>
        <p><?= HTML::anchor('services/search/metro_'.$metro_id, __('filter_tag_metro', array(':name' => $metro_name))); ?></p>
    <?php endforeach; ?>
</div>




