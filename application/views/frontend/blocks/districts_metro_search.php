<?php
defined('SYSPATH') or die('No direct script access.');
?>
<div class="m_d">
    <?php if (!empty($districts)): ?>
        Округ:
        <select onChange="if(this.options[this.selectedIndex].value!=''){window.location=this.options[this.selectedIndex].value}else{this.options[selectedIndex=0];}">
            <option value="<?= URL::base().$url.$city_id_url.$item_id_url ?>">округ</option>
            <?php foreach ($districts as $id => $name): ?>
            <?php
            $selected = '';
            if ($district_id == $id)
            {
                $selected = 'selected = "selected"';
            }
            ?>
                <option value="<?= URL::base().$url.$city_id_url.'/district_'.$id.$item_id_url; ?>" <?= $selected ?>><?= $name; ?></option>
            <?php endforeach; ?>
        </select>

    <?php endif; ?>

    <?php if (!empty($metro)): ?>
        Метро:
        <select onChange="if(this.options[this.selectedIndex].value!=''){window.location=this.options[this.selectedIndex].value}else{this.options[selectedIndex=0];}">
            <option value="<?= URL::base().$url.$city_id_url.$district_id_url.$item_id_url ?>">станция метро</option>
            <?php foreach ($metro as $id => $name): ?>
            <?php
            $selected = '';
            if ($metro_id == $id)
            {
                $selected = 'selected = "selected"';
            }
            ?>
                <option value="<?= URL::base().$url.$city_id_url.$district_id_url.'/metro_'.$id.$item_id_url; ?>" <?= $selected ?>><?= $name; ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
</div>