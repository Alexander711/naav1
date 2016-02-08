<?php
defined('SYSPATH') or die('No direct script access.');
$checkboxes = array(
    'active' => array(
        'val' => 1,
        'label' => 'Активен'
    )
);
$css_auto_works = '';
if (Arr::get($values, 'type', 1) == 2)
{
    $css_auto_works = 'style="display: none;"';
}
echo TbForm::open(NULL, array('class' => 'form-horizontal'), $values, $errors);
?>
<ul class="nav nav-pills">
    <li class="active"><a href="#">Информация</a></li>
    <li><?= HTML::anchor('admin/services/gallery/'.$service->id, 'Галерея'); ?></li>
</ul>
<fieldset>
    <legend>Пользователь зарегистрировавший компанию</legend>
    <div class="control-group">
        <label class="control-label">Имя пользователя</label>
        <div class="controls">
            <?= FORM::input(NULL, $user->username, array('class' => 'input-xlarge disabled', 'disabled' => 'disabled')) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls">
            <?= FORM::input(NULL, $user->email, array('class' => 'input-xlarge disabled', 'disabled' => 'disabled')) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" style="padding-top: 0;">Дата рег. / посл. вх.</label>
        <div class="controls">
            <?= MyDate::show_small($user->date_create); ?> / <?= ($user->last_login != NULL) ? date('d.m.Y', $user->last_login) : 'ни разу'; ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Действия</label>
        <div class="controls">
            <ul style="list-style-type: none; margin: 0;">
                <li style="float: left;"><?= HTML::anchor('admin/users/edit_info/'.$user->id, 'Редактировать данные пользователя', array('class' => 'btn btn-primary')); ?></li>
                <li style="float: left; padding-left: 10px;">
                <div class="btn-group">
                    <a href="#" class="btn dropdown-toggle" data-toggle="dropdown">Отправить сообщение <span class="caret"></span></a>
                    <ul class="dropdown-menu" style="max-width: none;">
                        <li><?= HTML::anchor('admin/notice/add_user/'.$user->id, 'Отправить уведомление пользователю') ?></li>
                        <li><?= HTML::anchor('#', 'Отправить сообщение по почте') ?></li>
                    </ul>
                </div>
                </li>
            </ul>



        </div>
    </div>
</fieldset>
<div class="row-fluid">
    <div class="span6">
        <fieldset class="service">
            <legend>Параметры компании</legend>
            <?= TbForm::checkboxes($checkboxes, 'Статус') ?>
            <div class="control-group">
                <label class="control-label"><?= __('f_company_type'); ?></label>
                <div class="controls">
                    <?= FORM::select('type', $company_types, Arr::get($values, 'type'), array('id' => 'company_type')); ?>
                </div>
            </div>
            <?= TbForm::radiobuttons('org_type', $org_types, array('label' => __('f_org_type'))) ?>
            <?= TbForm::input('name', array('label' => __('f_full_company_name'))); ?>
            <?= TbForm::input('inn', array('label' => __('f_inn'))); ?>
            <?= TbForm::input('director_name', array('label' => __('f_director_name'))); ?>
            <?= TbForm::input('contact_person', array('label' => __('f_contact_person'))); ?>



        </fieldset>
    </div>
    <div class="span6">
        <fieldset class="service">
            <legend>
                Реквизиты компании
            </legend>
            <div class="control-group" id="city">
                <label class="control-label"><?= __('f_city'); ?></label>
                <div class="controls">
                    <?= FORM::select('city_id', array('' => 'Выбрать город') + $cities, Arr::get($values, 'city_id')); ?>
                </div>
            </div>
            <div class="control-group district" <?php if (!count($districts)) echo 'style="display: none;"' ?>>
                <label class="control-label"><?= __('f_district'); ?></label>
                <div class="controls">
                    <?= FORM::select('district_id', $districts, Arr::get($values, 'district_id'), array('class' => 'district_select')); ?>
                </div>
            </div>
            <div class="control-group metro" <?php if (!count($stations)) echo 'style="display: none;"' ?>>
                <label class="control-label"><?= __('f_metro'); ?></label>
                <div class="controls">
                    <?= FORM::select('metro_id', $stations, Arr::get($values, 'metro_id'), array('class' => 'metro_select')); ?>
                </div>
            </div>
            <?= TbForm::input('address', array('label' => __('f_address'))); ?>
            <div class="control-group">
                <label class="control-label"><?= __('f_phone'); ?></label>
                <div class="controls">
                    +7 <?= FORM::input('code', Arr::get($values, 'code'), array('style' => 'width: 40px;')); ?> <?= FORM::input('phone', Arr::get($values, 'phone')); ?>
                </div>
            </div>
            <?= TbForm::input('fax', array('label' => __('f_fax'))); ?>
            <?= TbForm::input('site', array('label' => __('f_site'))); ?>
        </fieldset>
    </div>
</div>
<div class="row">
    <div class="span16">
        <fieldset>
            <legend>Услуги и прочее</legend>
            <div class="control-group">
                <label class="control-label"><?= __('f_auto_models'); ?></label>
                <div class="controls">
                    <select id="auto_models" multiple="multiple" name="model[]" style="width: 400px;">
                    <?php foreach ($auto_models as $id => $name): ?>
                    <?php
                    $selected = '';
                    if (in_array($id, Arr::get($values, 'model', array())))
                    {
                        $selected = 'selected = "selected"';
                    }
                    ?>
                        <option value="<?= $id ?>" <?= $selected ?>><?= $name; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-left: 160px;" id="selected_models"></div>
            </div>
            <div class="control-group works" <?= $css_auto_works ?>>
                <label class="control-label"><?= __('f_works'); ?></label>
                <div class="controls">
                    <select id="works" multiple="multiple" name="work[]" style="width: 400px;">
                        <?php foreach ($work_category->find_all() as $category): ?>
                            <optgroup label="<?= $category->name; ?>">
                                <?php foreach ($category->works->find_all() as $work): ?>
                                <?php
                                $selected = '';
                                if (in_array($work->id, Arr::get($values, 'work', array())))
                                {
                                    $selected = 'selected = "selected"';
                                }
                                ?>
                                    <option value="<?= $work->id ?>" <?= $selected ?>><?= $work->name; ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-left: 160px;" id="selected_works"></div>
            </div>
            <?= TbForm::input('work_time', array('label' => __('f_work_time'), 'size' => 2)); ?>
            <?= TbForm::textarea('about', array('label' => __('f_about_service'), 'horizontal' => FALSE)) ?>
            <div class="control-group">
                <label class="control-label"><?= __('f_discount') ?></label>
                <div class="controls">
                    <?= FORM::select('discount_id', array('0' => 'нет') + $discounts, Arr::get($values, 'discount_id'), array('id' => 'discount')); ?>
                </div>
            </div>
            <div class="control-group coupon_text">
                <label class="control-label"><?= __('f_coupon_text'); ?></label>
                <div class="controls">
                    <?= FORM::textarea('coupon_text', Arr::get($values, 'coupon_text'), array('class' => 'span6')); ?>
                </div>
            </div>
        </fieldset>
    </div>
</div>
<?= TbForm::actions('save_and_reset'); ?>
<?= FORM::close(); ?>