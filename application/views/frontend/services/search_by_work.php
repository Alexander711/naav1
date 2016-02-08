<?php
defined('SYSPATH') or die('No direct script access.');
$all_cities_class = array();
if ($all_city_selected)
    $all_cities_class['class'] ='active';
$city_id_url = '';
$district_id_url = '';
$metro_id_url = '';
if ($city_id != NULL)
{
    $city_id_url = '/city_'.$city_id;
}
if ($district_id != NULL AND !empty($districts))
{
    $district_id_url = '/district_'.$district_id;
}
if ($metro_id != NULL AND !empty($metro))
{
    $metro_id_url = '/metro_'.$metro_id;
}
$all_discounts_class = array();
if (!$discount_id)
{
    $all_discounts_class['class'] ='active';
}
?>
<?= Message::render(); ?>
<div style="margin: 5px 0;">
    <?= $content; ?>
</div>
<div class="cities_navigation">
    <ul>
        <li><?= HTML::anchor('services/search/work_'.$work_id, 'Все', array('rel' => 0) + $all_cities_class) ?></li>
        <?php foreach ($cities as $id => $name): ?>
            <?php if ($id == $city_id): ?>
                <li><?= HTML::anchor('#', $name, array('rel' => $id, 'class' => 'active')); ?></li>
            <?php else: ?>
                <li><?= HTML::anchor('services/search/city_'.$id.'/work_'.$work_id, $name, array('rel' => $id)); ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</div>
<div class="d_m_and_pagination">
    <?php if (!empty($districts) OR !empty($metro)): ?>
    <?php
    echo View::factory('frontend/blocks/districts_metro_search')
             ->set('districts', $districts)
             ->set('metro', $metro)
             ->set('city_id', $city_id)
             ->set('district_id', $district_id)
             ->set('metro_id', $metro_id)
             ->set('city_id_url', $city_id_url)
             ->set('district_id_url', $district_id_url)
             ->set('item_id_url', $metro_id_url)
             ->set('item_id_url', '/work_'.$work_id)
             ->set('url', 'services/search')
             ->render();
    ?>
    <?php endif; ?>
</div>
<div class="search_result">
<div class="left_search">
    <div class="discount_filter">
        <div class="title">Скидки</div>

        <div class="discounts_list">
            <ul>

                <li><?= HTML::anchor('services/search'.$city_id_url.$district_id_url.$metro_id_url.'/work_'.$work_id, 'Любые', $all_discounts_class) ?></li>
            <?php foreach ($discounts as $id => $name): ?>
                <?php if ($id == $discount_id): ?>
                    <li><?= HTML::anchor('services/search'.$city_id_url.$district_id_url.$metro_id_url.'/discount_'.$id.'/work_'.$work_id, 'Сервисы по скидкой '.$name.'%', array('class' => 'active')) ?></li>
                <?php else: ?>
                    <li><?= HTML::anchor('services/search'.$city_id_url.$district_id_url.$metro_id_url.'/discount_'.$id.'/work_'.$work_id, 'Сервисы по скидкой '.$name.'%') ?></li>
                <?php endif; ?>


            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="work_filter">
    <div class="title">Сортировка по авто</div>
    <?= FORM::open('#').FORM::hidden('city_id', $city_id, array('class' => 'city_id_search_by_work'))
       .FORM::hidden('work_id', $work_id, array('class' => 'work_id_search_by_work'))
       .FORM::hidden('district_id', $district_id, array('class' => 'district_id_search_by_work'))
       .FORM::hidden('metro_id', $metro_id, array('class' => 'metro_id_search_by_work'))
       .FORM::hidden('discount_id', $discount_id, array('class' => 'discount_id_search_by_work'))
       .FORM::close();
    ?>

    <div class="cars_list">
         <ul>
        <?php foreach ($cars as $id => $name): ?>
        <li><?= FORM::checkbox('car[]', $id, FALSE, array('class' => 'search_cars', 'id' => 'car_'.$id, 'autocomplete' => 'off')); ?> <label for="car_<?= $id ?>"><?= $name; ?></label></li>
        <?php endforeach; ?>
         </ul>
    </div>

    </div>

</div>
<div class="right_search">
    <?= View::factory('frontend/blocks/search/services')->set('services', $services)->render(); ?>
</div>
<div class="clear"></div>
</div>
