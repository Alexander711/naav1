<?php
defined('SYSPATH') or die('No direct script access.');
$org_type = Arr::get($values, 'org_type', FALSE);
$selected_works = Arr::get($values, 'work', array());
$selected_cars = Arr::get($values, 'model', array());
//echo count($selected_cars);
$phone_inp_style = '';
$address_inp_style = '';
$name_inp_style = '';
if (Arr::get($errors, 'phone'))
{
    $phone_inp_style = 'border: 1px solid brown;';
}
if (Arr::get($errors, 'address'))
{
    $address_inp_style = 'border: 1px solid brown';
}
if (Arr::get($errors, 'name'))
{
    $name_inp_style = 'border: 1px solid brown';
}
echo FORM::open($url,array('autocomplete' => 'off'));
$witch_works_models = Arr::get($values, 'witch_w_m', FALSE);
$discounts = array('0' => 'нет') + $discounts;
?>
<?php if (Request::current()->action() == 'edit'): ?>
    <ul class="nav nav-tabs">
        <li class="active"><a href="#">Данные компании</a></li>
        <li><?= HTML::anchor('cabinet/company/gallery/'.$company->id, 'Галерея'); ?></li>
    </ul>
<?php endif; ?>
<div class="st_form">
    <div class='field_body'>
        <a href='javascript:void(0)' class='add_group' data-next_group='2'>Добавить категорию</a>
    </div>
    <div class="li_group_1 field_body">
        <label for="group_id_1" class="lab"><?= __('f_company_type') ?></label>
        <?= FORM::select('group_id[]', $groups, Arr::get($values, 'group_id'),array('class' => 'select_group','id' => 'group_id_1','data-number_group' => '1')); ?>
    </div>
    <div class='field_body'>
        <label class="lab"><?= __('f_org_type') ?></label>
        <?php foreach ($org_types as $value => $text): ?>
            <?php $selected = ($org_type == $value) ? TRUE : FALSE; ?>
            <?= FORM::radio('org_type', $value, $selected).$text ?>
        <?php endforeach; ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'org_type'); ?></div>
    </div>
    <div class='field_body'>
        <label for="name" class="lab"><?= __('f_full_company_name') ?></label>
        <?= FORM::input('name', Arr::get($values, 'name'), array('class' => 's_inp', 'id' => 'name', 'style' => $name_inp_style)); ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'name'); ?></div>
    </div>
    <div class='field_body'>
        <label for="inn" class="lab"><?= __('f_inn') ?></label>
        <?= FORM::input('inn', Arr::get($values, 'inn'), array('class' => 's_inp', 'id' => 'inn')); ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'inn'); ?></div>
    </div>
    <div class='field_body'>
        <label for="director_name" class="lab"><?= __('f_director_name'); ?></label>
        <?= FORM::input('director_name', Arr::get($values, 'director_name'), array('class' => 's_inp', 'id' => 'director_name')); ?>
    </div>
    <div class='field_body'>
        <label for="contact_person" class="lab"><?= __('f_contact_person'); ?></label>
        <?= FORM::input('contact_person', Arr::get($values, 'contact_person'), array('class' => 's_inp', 'id' => 'contact_person')); ?>
    </div>
    <div class='field_body'>
        <?= __('f_location') ?>
    </div>
    <div class='field_body'>
        <label for="city_name" class="lab"><?= __('f_city'); ?></label>
        <?= FORM::input('city_name', Arr::get($values, 'city_name'), array('class' => 's_inp', 'id' => 'city_name')); ?>
        <?= HTML::anchor('feedback/add_city', 'Не нашли свой город?', array('target' => '_blank')); ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'city_id'); ?></div>
    </div>
    <?php
    $metros = array();
    $districts = array();

    $current_city = $city->where('id', '=', Arr::get($values, 'city_name', FALSE))->find();
    foreach ($current_city->metro->find_all() as $metro)
    {
        $metros[$metro->id] = $metro->name;
    }
    foreach ($current_city->districts->find_all() as $d)
    {
        $districts[$d->id] = $d->name;
    }
    ?>
    <div class="district field_body" style="<?= (!empty($districts)) ? 'display: block;' : '' ?>">
        <label class="lab"><?= __('f_district') ?></label>
        <?= Form::select('district_id', $districts, Arr::get($values, 'district_id'), array('class' => 'district_select')); ?>
    </div>
    <div class="metro field_body" style="<?= (!empty($metros)) ? 'display: block;' : '' ?>">
        <label class="lab"><?= __('f_metro') ?></label>
        <?= Form::select('metro_id', $metros, Arr::get($values, 'metro_id'), array('class' => 'metro_select')); ?>
    </div>
    <div class='field_body'>
        <label for="address" class="lab"><?= __('f_address'); ?></label>
        <?= FORM::input('address', Arr::get($values, 'address'), array('class' => 's_inp', 'id' => 'address', 'style' => $address_inp_style)); ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'address'); ?></div>
    </div>
    <div class='field_body'>
        <label class="lab"><?= __('f_map_location'); ?></label>
        <div id="edit-map"></div>
        <div class="map-error"></div>
        <?= FORM::hidden('ymap_lat', Arr::get($values, 'ymap_lat')).FORM::hidden('ymap_lng', Arr::get($values, 'ymap_lng')) ?>
    </div>
    <div class='field_body'>
        <label for="phone" class="lab"><?= __('f_phone'); ?></label>
        +7 <?= FORM::input('code', Arr::get($values, 'code'), array('class' => 's_inp', 'style' => 'width: 40px;')); ?> <?= FORM::input('phone', Arr::get($values, 'phone'), array('class' => 's_inp', 'id' => 'phone', 'style' => $phone_inp_style.' width: 267px;')); ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'code'); ?></div>
        <div class="form_error"><?= Message::show_once_error($errors, 'phone'); ?></div>
    </div>
    <div class='field_body'>
        <label for="fax" class="lab"><?= __('f_fax'); ?></label>
        <?= FORM::input('fax', Arr::get($values, 'fax'), array('class' => 's_inp', 'id' => 'fax')); ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'fax'); ?></div>
    </div>
    <div class='field_body'>
        <label for="site" class="lab"><?= __('f_site'); ?></label>
        <?= FORM::input('site', Arr::get($values, 'site'), array('class' => 's_inp', 'id' => 'site')); ?>
    </div>
    <div class="auto_models field_body">
        <label for="auto_models" class="lab"><?= __('f_auto_models'); ?></label>
        <select id="auto_models" multiple="multiple" name="model[]" style="width: 400px;">
        <?php foreach ($auto_models as $id => $name): ?>
        <?php
        $selected = '';
        if (in_array($id, $selected_cars))
        {
            $selected = 'selected = "selected"';
        }
        ?>
            <option value="<?= $id ?>" <?= $selected ?>><?= $name; ?></option>
        <?php endforeach; ?>
        </select>

        <div id="selected_models"></div>
    </div>
    <div class="works field_body">
        <label for="works" class="lab"><?= __('f_works'); ?></label>
        <select id="works" multiple="multiple" name="work[]" style="width: 400px;">
            <?php foreach ($work_category->find_all() as $category): ?>
                <optgroup label="<?= $category->name; ?>">
                    <?php foreach ($category->works->find_all() as $work): ?>
                    <?php
                    $selected = '';
                    if (in_array($work->id, $selected_works))
                    {
                        $selected = 'selected = "selected"';
                    }
                    ?>
                        <option value="<?= $work->id ?>" <?= $selected ?>><?= $work->name; ?></option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
        <div id="selected_works"></div>
    </div>
    <div class='field_body'>
        <label for="about" class="lab"><?= __('f_about_service') ?></label>
        <?= FORM::textarea('about', Arr::get($values, 'about'), array('class' => 's_tarea', 'id' => 'about', 'style' => 'width: 400px;')) ?>
        <div class="form_error"><?= Message::show_once_error($errors, 'about'); ?></div>
    </div>
    <div class='field_body'>
        <label for="hour_start" class="lab"><?= __('f_work_time') ?></label>
        <?= FORM::input('work_times', Arr::get($values, 'work_times'), array('class' => 's_inp')) ?>
    </div>
    <div class='field_body'>
        <label class="lab"><?= __('f_discount') ?></label>
        <?= FORM::select('discount_id', $discounts, Arr::get($values, 'discount_id'), array('id' => 'discount')); ?>
    </div>
    <?php
    $discount =Arr::get($values, 'discount_id', 0);
    $coupon_form_style = 'display: none';
    if ($discount != 0)
    {
        $coupon_form_style = 'display: block';
    }

    ?>
    <div class="coupon_text field_body" style="<?= $coupon_form_style; ?>">
        <label class="lab"><?= __('f_coupon_text'); ?></label>
        <?= FORM::textarea('coupon_text', Arr::get($values, 'coupon_text'), array('class' => 's_tarea', 'style' => 'width: 400px; height: 100px;')); ?>
    </div>
    <div class='field_body'>
        <label class="lab">&nbsp;</label>
        <?= FORM::submit('submit', __('f_continue'), array('class' => 's_button')); ?>
    </div>
</div>
<?= FORM::close(); ?>