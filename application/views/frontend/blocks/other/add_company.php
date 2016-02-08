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
echo FORM::open($url);
$witch_works_models = Arr::get($values, 'witch_w_m', FALSE);
$discounts = array('0' => 'нет') + $discounts;
?>
<div class="st_form">
    <ul>
        <li>
            <label for="company_type" class="lab"><?= __('f_company_type') ?></label>
            <?= FORM::select('type', $company_types, Arr::get($values, 'type'), array('id' => 'company_type')); ?>
        </li>
        <li>
            <label class="lab"><?= __('f_org_type') ?></label>
            <?php foreach ($org_types as $value => $text): ?>
                <?php $selected = ($org_type == $value) ? TRUE : FALSE; ?>
                <?= FORM::radio('org_type', $value, $selected).$text ?>
            <?php endforeach; ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'org_type'); ?></div>
        </li>
        <li>
            <label for="name" class="lab"><?= __('f_full_company_name') ?></label>
            <?= FORM::input('name', Arr::get($values, 'name'), array('class' => 's_inp', 'id' => 'name', 'style' => $name_inp_style)); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'name'); ?></div>
        </li>
        <li>
            <label for="inn" class="lab"><?= __('f_inn') ?></label>
            <?= FORM::input('inn', Arr::get($values, 'inn'), array('class' => 's_inp', 'id' => 'inn')); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'inn'); ?></div>
        </li>
        <li>
            <label for="director_name" class="lab"><?= __('f_director_name'); ?></label>
            <?= FORM::input('director_name', Arr::get($values, 'director_name'), array('class' => 's_inp', 'id' => 'director_name')); ?>
        </li>
        <li>
            <label for="contact_person" class="lab"><?= __('f_contact_person'); ?></label>
            <?= FORM::input('contact_person', Arr::get($values, 'contact_person'), array('class' => 's_inp', 'id' => 'contact_person')); ?>
        </li>
        <li>
            <?= __('f_location') ?>
        </li>
        <li>
            <label for="city" class="lab"><?= __('f_city'); ?></label>
            <?= FORM::select('city_id', array('' => 'Выбрать город') + $cities, Arr::get($values, 'city_id'), array('id' => 'city')); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'city_id'); ?></div>
        </li>
        <?php
        $metros = array();
        $districts = array();

        $current_city = $city->where('id', '=', Arr::get($values, 'city_id', FALSE))->find();
        foreach ($current_city->metro->find_all() as $metro)
        {
            $metros[$metro->id] = $metro->name;
        }
        foreach ($current_city->districts->find_all() as $d)
        {
            $districts[$d->id] = $d->name;
        }
        ?>
        <li class="district" style="<?= (!empty($districts)) ? 'display: block;' : '' ?>">
            <label class="lab"><?= __('f_district') ?></label>
            <?= Form::select('district_id', $districts, Arr::get($values, 'district_id'), array('class' => 'district_select')); ?>
        </li>
        <li class="metro" style="<?= (!empty($metros)) ? 'display: block;' : '' ?>">
            <label class="lab"><?= __('f_metro') ?></label>
            <?= Form::select('metro_id', $metros, Arr::get($values, 'metro_id'), array('class' => 'metro_select')); ?>
        </li>
        <li>
            <label for="address" class="lab"><?= __('f_address'); ?></label>
            <?= FORM::input('address', Arr::get($values, 'address'), array('class' => 's_inp', 'id' => 'address', 'style' => $address_inp_style)); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'address'); ?></div>
        </li>
        <li>
            <label for="phone" class="lab"><?= __('f_phone'); ?></label>
            +7 <?= FORM::input('code', Arr::get($values, 'code'), array('class' => 's_inp', 'style' => 'width: 40px;')); ?> <?= FORM::input('phone', Arr::get($values, 'phone'), array('class' => 's_inp', 'id' => 'phone', 'style' => $phone_inp_style.' width: 267px;')); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'code'); ?></div>
            <div class="form_error"><?= Message::show_once_error($errors, 'phone'); ?></div>
        </li>
        <li>
            <label for="fax" class="lab"><?= __('f_fax'); ?></label>
            <?= FORM::input('fax', Arr::get($values, 'fax'), array('class' => 's_inp', 'id' => 'fax')); ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'fax'); ?></div>
        </li>
        <li>
            <label for="site" class="lab"><?= __('f_site'); ?></label>
            <?= FORM::input('site', Arr::get($values, 'site'), array('class' => 's_inp', 'id' => 'site')); ?>
        </li>
        <?php
        $css_auto_works = '';
        if ($type == 2)
        {
            $css_auto_works = 'style="display: none;"';
        }
        ?>
        <li class="auto_models" <?= $css_auto_works ?>>
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
        </li>
        <li class="works" <?= $css_auto_works ?>>
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
        </li>
        <li>
            <label for="about" class="lab"><?= __('f_about_service') ?></label>
            <?= FORM::textarea('about', Arr::get($values, 'about'), array('class' => 's_tarea', 'id' => 'about', 'style' => 'width: 400px;')) ?>
            <div class="form_error"><?= Message::show_once_error($errors, 'about'); ?></div>
        </li>
        <li>
            <label for="hour_start" class="lab"><?= __('f_work_time') ?></label>
            <?= FORM::input('work_times', Arr::get($values, 'work_times'), array('class' => 's_inp')) ?>
        </li>
        <li>
            <label class="lab"><?= __('f_discount') ?></label>
            <?= FORM::select('discount_id', $discounts, Arr::get($values, 'discount_id'), array('id' => 'discount')); ?>
        </li>
        <?php
        $discount =Arr::get($values, 'discount_id', 0);
        $coupon_form_style = 'display: none';
        if ($discount != 0)
        {
            $coupon_form_style = 'display: block';
        }

        ?>
        <li class="coupon_text" style="<?= $coupon_form_style; ?>">
            <label class="lab"><?= __('f_coupon_text'); ?></label>
            <?= FORM::textarea('coupon_text', Arr::get($values, 'coupon_text'), array('class' => 's_tarea', 'style' => 'width: 400px; height: 100px;')); ?>
        </li>
        <li>
            <label class="lab">&nbsp;</label>
            <?= FORM::submit('submit', __('f_continue'), array('class' => 's_button')); ?>
        </li>
    </ul>
</div>
<?= FORM::close(); ?>