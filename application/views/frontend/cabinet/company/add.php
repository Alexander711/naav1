<?php
defined('SYSPATH') or die('No direct script access.');
$selected_works = Arr::get($values, 'work', array());
$selected_models = Arr::get($values, 'model', array());
echo FORM::open('cabinet/company/add');
?>
<table class="add_item_table">
    <!-- Type -->
    <tr>
        <td><label class="label" for="company_type"><?= __('f_company_type'); ?></label></td>
        <td ><?= FORM::select('company_type', $company_types, Arr::get($values, 'company_type'), array('id' => 'company_type')); ?></td>
        <td><?= NError::show_error('company_type', $errors); ?></td>
    </tr>
    <!-- Org. type -->
    <tr>
        <td><label class="label"><?= __('f_org_type') ?></label></td>
        <td>
            <?php foreach ($org_types as $value => $text): ?>
                <?php $selected = (Arr::get($values, 'org_type') == $value) ? TRUE : FALSE; ?>
                <?= FORM::radio('org_type', $value, $selected).$text ?>
            <?php endforeach; ?>
        </td>
        <td>
            <?= NError::show_error('org_type', $errors); ?>
        </td>
    </tr>
    <!-- Name -->
    <tr>
        <td><label class="label" for="name"><?= __('f_full_company_name') ?></label></td>
        <td><?= FORM::input('name', Arr::get($values, 'name'), array('class' => 's_inp', 'id' => 'name')); ?></td>
        <td><?= NError::show_error('name', $errors); ?></td>
    </tr>
    <!-- Inn -->
    <tr>
        <td><label class="label" for="inn"><?= __('f_inn') ?></label></td>
        <td><?= FORM::input('inn', Arr::get($values, 'inn'), array('class' => 's_inp', 'id' => 'inn')); ?></td>
    </tr>
    <!-- Director name -->
    <tr>
        <td><label class="label" for="director_name"><?= __('f_director_name'); ?></label></td>
        <td><?= FORM::input('director_name', Arr::get($values, 'director_name'), array('class' => 's_inp', 'id' => 'director_name')); ?></td>
    </tr>
    <!-- Contact person -->
    <tr>
        <td><label class="label" for="contact_person"><?= __('f_contact_person'); ?></label></td>
        <td><?= FORM::input('contact_person', Arr::get($values, 'contact_person'), array('class' => 's_inp', 'id' => 'contact_person')); ?></td>
    </tr>
    <tr>
        <td>
            <?= __('f_location') ?>
        </td>
    </tr>
    <!-- City -->
    <tr>
        <td><label class="label" for="city"><?= __('f_city'); ?></label></td>
        <td><?= FORM::select('city_id', array('' => '-') + $cities, Arr::get($values, 'city_id'), array('id' => 'city')); ?></td>
    </tr>
    <!-- Metro -->
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

    <tr class="metro" style="<?= (!empty($metros)) ? 'display: table-row;' : '' ?>">
        <td><label class="label"><?= __('f_metro') ?></label></td>
        <td class="select"><?= Form::select('metro_id', $metros, Arr::get($values, 'metro_id')); ?></td>
    </tr>

    <tr class="district" style="<?= (!empty($districts)) ? 'display: table-row;' : '' ?>">
        <td><label class="label"><?= __('f_district') ?></label></td>
        <td class="select"><?= Form::select('district_id', $districts, Arr::get($values, 'district_id')); ?></td>
    </tr>

    <!-- Address -->
    <tr>
        <td><label class="label" for="address"><?= __('f_address'); ?></label></td>
        <td><?= FORM::input('address', Arr::get($values, 'address'), array('class' => 's_inp', 'id' => 'address')); ?></td>
        <td><?= NError::show_error('address', $errors); ?></td>
    </tr>
    <!-- Phone -->
    <tr>
        <td><label class="label" for="phone"><?= __('f_phone'); ?></label></td>
        <td><?= FORM::input('phone', Arr::get($values, 'phone'), array('class' => 's_inp', 'id' => 'phone')); ?></td>
        <td><?= NError::show_error('phone', $errors); ?></td>
    </tr>
    <!-- Fax -->
    <tr>
        <td><label class="label" for="fax"><?= __('f_fax'); ?></label></td>
        <td><?= FORM::input('fax', Arr::get($values, 'fax'), array('class' => 's_inp', 'id' => 'fax')); ?></td>
    </tr>
    <!-- Site -->
    <tr>
        <td><label class="label" for="site"><?= __('f_site'); ?></label></td>
        <td><?= FORM::input('site', Arr::get($values, 'site'), array('class' => 's_inp', 'id' => 'site')); ?></td>
    </tr>
</table>
<table class="add_item_table">
    <!-- auto models -->
    <tr>
        <td>
            <label class="label" for="auto_models"><?= __('f_auto_models'); ?></label>
        </td>
        <td>
            <select id="auto_models" multiple="multiple" name="model[]">
                <?php foreach ($auto_models->find_all() as $model): ?>
                <?php
                $selected = '';
                if (in_array($model->id, $selected_models))
                {
                    $selected = 'selected = "selected"';
                }
                ?>
                    <option value="<?= $model->id ?>" <?= $selected ?>><?= $model->name; ?></option>
                <?php endforeach; ?>
            </select>
            <div id="selected_models"></div>
        </td>
        <td>
            <?= NError::show_error('model', $errors); ?>
        </td>
    </tr>
    <!-- works -->
    <tr>
        <td>
            <label class="label" for="works"><?= __('f_works'); ?></label>
        </td>
        <td>
            <select id="works" multiple="multiple" name="work[]">
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
        </td>
        <td>
            <?= NError::show_error('work', $errors); ?>
        </td>
    </tr>
    <!-- about -->
    <tr>
        <td style="vertical-align: top">
            <label class="label" for="about"><?= __('f_about_service') ?></label>
        </td>
        <td>
            <?= FORM::textarea('about', Arr::get($values, 'about'), array('class' => 's_tarea', 'id' => 'about')) ?>
        </td>
    </tr>
    <!-- work time -->
    <tr>
        <td>
            <label class="label" for="hour_start"><?= __('f_work_time') ?></label>
        </td>
        <td>
            с <?= FORM::input('hour_start', Arr::get($values, 'hour_start'), array('class' => 's_inp', 'style' => 'width: 20px;')) ?>
            по <?= FORM::input('hour_finish', Arr::get($values, 'hour_finish'), array('class' => 's_inp', 'style' => 'width: 20px;')) ?> ч.
        </td>
        <td>
            <?= NError::show_error('hour_start', $errors).NError::show_error('hour_finish', $errors) ?>
        </td>
    </tr>
</table>
<?= FORM::submit('continue', __('f_continue')); ?>
<?= FORM::close(); ?>
