<?php
defined('SYSPATH') or die('No direct script access.');
$attr = array();
?>
<div class="row">
    <?= $content->text; ?>
</div>
<div class="filter_navigation">
    Подбор сервиса по: <?= HTML::anchor('filter/auto', 'Марке автомобиля').HTML::anchor('filter/work', 'Услуге').HTML::anchor('filter/metro', 'Станции метро').HTML::anchor('filter/district', 'Округу', array('class' => 'active')); ?>
</div>
<div class="cities_navigation" style="clear: both;">
    <ul>
        <?php foreach ($cities as $id => $name): ?>
            <?php
            $attr['class'] = ($id == $city_id) ? 'active' : '';
            ?>
            <li><?= HTML::anchor('filter/district/city_'.$id, $name, $attr); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php
$div_attr['class'] = 'filter_tags columnize';
if (count($districts) < 5)
    $div_attr['class'] = 'filter_tags';
?>
<div <?= HTML::attributes($div_attr); ?>>
    <?php foreach ($districts as $id => $value): ?>
        <p><?= HTML::anchor('services/search/district_'.$id, __('filter_tag_district', array(':city_name' => $value['city']['genitive_name'], ':district_name' => $value['name']))); ?></p>
    <?php endforeach; ?>
</div>