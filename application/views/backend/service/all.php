<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div class="row">
    <div class="span8" style="width: 45%">
        <ul class="nav nav-pills">
            <?php foreach ($types as $id => $value): ?>
                <li class="<?= $value['css_class']; ?>"><?= HTML::anchor('admin/services/type_'.$id, $value['name']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div style="clear: both;">
    <ul class="nav nav-tabs">
    <?php foreach ($cities as $id => $name): ?>
        <?php
        $css_class ='';
        if ($id == $current_city)
        {
            $css_class = 'active';
        }
        ?>
        <li class="<?= $css_class ?>"><?= HTML::anchor('admin/services/type_'.$type.'/city_'.$id, $name); ?></li>
    <?php endforeach; ?>
    </ul>
</div>
<?= View::factory('backend/search_block')
        ->set('url', 'admin/services/type_'.$type)
        ->set('values', $values);
?>


