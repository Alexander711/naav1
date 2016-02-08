<?php
defined('SYSPATH') or die('No direct script access.');
$select_attrs['data-type'] = 'search_by_'.$type;
if ($type == 'auto')
    $select_attrs['data-item'] = $car_id;
elseif ($type == 'work')
    $select_attrs['data-item'] = $work_id;
?>

<?= Message::render(); ?>
<!-- Content -->
<div class="row search_content">
    <h1><?= $h1_title; ?></h1>
    <?php if ($type == 'auto' AND trim($cars[$car_id]['current_object']->img_path)): ?>
        <?= HTML::image($cars[$car_id]['current_object']->img_path, array('style' => 'float: left; margin: 10px 30px 10px 10px;', 'alt' => 'Автосервис '.$content->car->get_car_name(TRUE))); ?>
    <?php endif; ?>
    <?= $content->text; ?>
</div>
<div class="row">

    <?=
    View::factory('frontend/navigation/districts_metro')
        ->set('districts', $districts)
        ->set('metro_stations', $metro_stations)
        ->set('type', 'search_by_'.$type)
        ->set('city_id', $city_id)
        ->set('select_attrs', $select_attrs)
        ->set('selected', array('metro_id' => $metro_id, 'district_id' => $district_id))
        ->render();
    ?>
</div>
<div class="row">

    <div  class="filters_column" >
				<a href="http://www.mims.ru/ru-RU/visiting/e-ticket/mims_2013.aspx?lang=ru-ru&utm_source=as-avtoservice.ru&utm_medium=Media&utm_campaign=barter">
					<?=HTML::image('assets/img/banners/334x125_2.gif');?>
				</a>
        <?php
        echo FORM::open(NULL);
        if ($city_id)
            echo FORM::hidden('city_id', $city_id);

        ?>
        <div class="hidden_inputs">
            <?php
             echo FORM::hidden('district_id', $district_id);
             echo FORM::hidden('metro_id', $metro_id);
            if ($type == 'auto')
                echo FORM::checkbox('car[]', $car_id, TRUE);
            elseif ($type == 'work')
                echo FORM::checkbox('work[]', $work_id, TRUE);
            ?>
        </div>
        Географические параметры поиска: <br />
        <div class="search_info">
            <div class="city">город <strong><?= $city->name; ?></strong></div>
            <div class="district"><?php if (array_key_exists($district_id, $districts)) echo 'округ <strong>'.$districts[$district_id]->name.'</strong>'; ?></div>
            <div class="metro"><?php if (array_key_exists($metro_id, $metro_stations)) echo 'станция метро <strong>'.$metro_stations[$metro_id]->name.'</strong>'; ?></div>
        </div>


        <?php if ($type == 'auto'): ?>

            <div class="search_item_info">
                Подбор сервиса по марке автомобиля: <strong><?= (!trim($cars[$car_id]['name'])) ? $cars[$car_id]['name_ru'] : $cars[$car_id]['name']; ?></strong>
            </div>
            <p><a href="/filter/auto/city_<?= $city_id; ?>">Другие марки авто</a></p>
            <div class="search_sort_lists">
                <?php if ($services_count == 1): ?>
                    <p class="sort_disable_message">Найден только один автосервис. Сортировка отключена.</p>
                <?php else: ?>
                    <?= View::factory('frontend/navigation/discounts')->set('discounts', $discounts)->render(); ?>
                    <?= View::factory('frontend/services/works_list')->set('works', $works)->render(); ?>
                <?php endif; ?>
            </div>

        <?php elseif ($type == 'work'): ?>

            <div class="search_item_info">
                Подбор сервиса по услуге: <strong><?= $content->work->name; ?></strong>
            </div>
            <p><a href="/filter/work/city_<?= $city_id; ?>">Другие услуги</a></p>

            <div class="search_sort_lists">
                <?php if ($services_count == 1): ?>
                    <p class="sort_disable_message">Найден только один автосервис. Сортировка отключена.</p>
                <?php else: ?>
                    <?= View::factory('frontend/navigation/discounts')->set('discounts', $discounts)->render(); ?>
                    <?= View::factory('frontend/services/cars_list')->set('cars', $cars)->render(); ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>



        <?php FORM::close(); ?>
    </div>
    <div class="search_result" style="width: 530px; float: right;">
         <?= View::factory('frontend/services/services_list')->set('services', $services)->render(); ?>
    </div>
</div>