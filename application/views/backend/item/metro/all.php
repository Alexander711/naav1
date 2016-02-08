<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?= View::factory('backend/navigation/metro_cities')->set('cities', $cities)->set('city_id', $city_id)->render(); ?>
<div class="clearfix">
    <ul class="nav nav-pills">
        <?php foreach ($types as $type): ?>
            <li <?= HTML::attributes($type['li_attrs']) ?>><?= HTML::anchor(Request::current()->uri().$type['url_query'], $type['text']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<p><?= HTML::anchor('admin/item/metro/add', 'Добавить станцию метро', array('class' => 'btn btn-primary')) ?></p>
<?= View::factory('backend/item/metro/'.$view_type)
        ->set('city', $current_city)
        ->set('metro', $metro); ?>

<?= View::factory('profiler/stats'); ?>