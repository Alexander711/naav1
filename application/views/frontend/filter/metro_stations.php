<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?= Message::render(); ?>
<div class="row">
    <?= $content->text; ?>
</div>
<?= View::factory('frontend/filter/navigation')
        ->set('current_filter', 'metro')
        ->set('selected_city_ids', $selected_city_ids)
        ->render();
?>
<?= View::factory('frontend/filter/cities_navigation')
        ->set('url', 'filter/metro')
        ->set('cities', $cities)
        ->set('city_id', $selected_city_ids['has_metro'])
        ->render();
?>
<?php
$img_metro_attr = array(
    'id' => 'metro-map-main',
    'style' => 'background: url(\''.$cities[$selected_city_ids['has_metro']]->img_metro_map_clear.'\') no-repeat; height: '.$cities[$selected_city_ids['has_metro']]->img_metro_height.'px; width: '.$cities[$selected_city_ids['has_metro']]->img_metro_width.'px; margin: 0 auto;'
);
?>

<div <?= HTML::attributes($img_metro_attr); ?>>
    <?php foreach ($metro_stations as $metro_id => $value): ?>
    <?php
    $marker_attrs = array(
        'class' => 'marker-icon'
    );
    if ($value->marker_top != 0 OR $value->marker_left != 0)
        $marker_attrs['style'] = 'top: '.$value->marker_top.'px; left: '.$value->marker_left.'px;';
    $name_attrs = array(
        'class' => 'name'
    );
    if ($value->name_top != 0 OR $value->name_left != 0)
        $name_attrs['style'] = 'top: '.$value->name_top.'px; left: '.$value->name_left.'px;';
    ?>
        <div class="metro-station" style="top: <?= $value->main_top; ?>px; left: <?= $value->main_left; ?>px">
            <div <?= HTML::attributes($marker_attrs); ?>></div>
            <div <?= HTML::attributes($name_attrs); ?>><?= HTML::anchor('services/search/metro_'.$metro_id, $value->name); ?></div>
        </div>
    <?php endforeach; ?>

</div>







