<?php
defined('SYSPATH') or die('No direct script access.');
$attr = array();
?>
<div class="row">
    <?= $content->text; ?>
</div>
<?= View::factory('frontend/filter/navigation')->set('current_filter', 'district')->set('selected_city_ids', $selected_city_ids)->render(); ?>
<?= View::factory('frontend/filter/cities_navigation')->set('url', 'filter/district')->set('cities', $cities)->set('city_id', $selected_city_ids['has_district'])->render(); ?>
<?php
$div_attr['class'] = 'filter_tags columnize';
if (count($districts) < 5)
    $div_attr['class'] = 'filter_tags';
?>
<div <?= HTML::attributes($div_attr); ?>>
    <?php foreach ($districts as $id => $value): ?>
        <p><?= HTML::anchor('services/search/district_'.$id, __('filter_tag_district', array(':city_name' => $value->city->genitive_name, ':district_name' => $value->name))); ?></p>
    <?php endforeach; ?>
</div>