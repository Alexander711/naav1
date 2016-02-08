<?php
defined('SYSPATH') or die('No direct script access.');
// Индексы часть url, напр filter/work, filter/auto
$items = array(
    'auto' => array(
        'city_id' => $selected_city_ids['has_cars'],
        'text' => 'Марке автомобиля'
    ),
    'work' => array(
        'city_id' => $selected_city_ids['has_works'],
        'text' => 'Услуге'
    ),
    'metro' => array(
        'city_id' => $selected_city_ids['has_metro'],
        'text' => 'Станции метро'
    ),
    'district' => array(
        'city_id' => $selected_city_ids['has_district'],
        'text' => 'Округу'
    )
);
?>
<div class="filter_navigation">
    Подбор сервиса по:
    <?php
    foreach ($items as $type => $item)
    {
        $attr['class'] = ($current_filter == $type) ? 'active' : NULL;
        $url = 'filter/'.$type.'/city_'.$item['city_id'];
        if ($selected_city_ids['total'] != $item['city_id'])
            $url .= '?selected_city='.$selected_city_ids['total'];
        echo HTML::anchor($url, $item['text'], $attr);
    }
    ?>
</div>