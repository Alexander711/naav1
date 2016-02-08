<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<!-- Content -->
<div class="row search_content">
    <h1><?= $h1; ?></h1>
    <?= $content->text; ?>
</div>
<!-- Filters AND services -->
<div class="row">
    <div class="filters_column">
        <object width="334" height="124" style="margin-left: 30px;" src="<?= URL::base() ?>assets/flash/3gp.swf">
            <embed width="334" height="124" align="top" src="<?= URL::base() ?>assets/flash/3gp.swf"/>
        </object>
        <?php
        echo FORM::open(NULL);
        echo FORM::hidden('district_id', $district_id);
        ?>
        <?php if ($services_count == 1): ?>
            <p>Найден только один автосервис. Сортировка отключена.</p>
        <?php else: ?>
            <?= View::factory('frontend/navigation/discounts')->set('discounts', $discounts)->render(); ?>
            <?= View::factory('frontend/services/cars_list')->set('cars', $cars)->render(); ?>
            <?= View::factory('frontend/services/works_list')->set('works', $works)->render(); ?>
        <?php endif; ?>
        <?= FORM::close(); ?>
    </div>
    <div class="search_result">
        <?= View::factory('frontend/services/services_list')->set('services', $services)->render(); ?>
    </div>
</div>


