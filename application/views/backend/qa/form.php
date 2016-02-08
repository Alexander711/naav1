<?php
defined('SYSPATH') or die('No direct script access.');
$checkboxes = array(
    'active' => array(
        'val' => 1,
        'label' => 'Активен'
    )
);
$send_checkboxes = array(
    'for_service_has_car' => array(
        'val' => 1,
        'label' => 'Предоставлящим ремонт указанной модели автомобиля'
    ),
    'for_service_address' => array(
        'val' => 1,
        'label' => 'Только из моего города'
    )
);
echo TbForm::open($url, array('class' => 'form-horizontal'), $values, $errors); ?>
<fieldset>
    <legend>Параметры запроса</legend>
    <?= TbForm::checkboxes($checkboxes, 'Статус'); ?>
</fieldset>
<fieldset>
    <legend>Параметры автомобиля</legend>
    <div class="control-group">
        <label class="control-label">Марка автомобиля</label>
        <div class="controls">
            <?= FORM::select('car_id', $car_brands, Arr::get($values, 'car_id'), array('id' => 'add_qa_car_brand')); ?>
            <?php if (Arr::get($errors, 'car_id')): ?>
                <p class="help-block"><?= $errors['car_id']; ?></p>
            <?php endif ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Модель автомобиля</label>
        <div class="controls">
            <?= FORM::select('model_id', $car_models, Arr::get($values, 'model_id'), array('id' => 'add_qa_models')); ?>
            <?php if (Arr::get($errors, 'model_id')): ?>
                <p class="help-block"><?= $errors['model_id']; ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?= TbForm::radiobuttons('gearbox_id', $gearboxes, array('label' => 'КПП')); ?>
    <?= TbForm::input('volume', array('label' => 'Объем', 'size' => 1)); ?>
    <?= TbForm::input('year', array('label' => 'Год выпуска', 'size' => 1)) ?>
    <?= TbForm::input('vin', array('label' => 'VIN', 'size' => 2)); ?>
    <div class="control-group">
        <label class="control-label">Перечень нужных работ</label>
        <div class="controls">
            <select id="works" multiple="multiple" name="work[]" style="width: 420px;">
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
            <div class="input"><div id="selected_works"></div></div>
        </div>
    </div>
    <?= TbForm::textarea('text', array('label' => 'Вопрос')); ?>
</fieldset>
<fieldset>
    <legend>Контактные данные</legend>
    <?= TbForm::input('contact', array('label' => 'Контактное лицо', 'size' => 3)); ?>
    <?= TbForm::input('email', array('label' => 'Email', 'size' => 3)); ?>
    <?= TbForm::input('phone', array('label' => 'Телефон', 'size' => 2)); ?>
    <div class="control-group">
        <label class="control-label">Город</label>
        <div class="controls">
            <?= FORM::select('city_id', $cities, Arr::get($values, 'city_id')); ?>
        </div>
    </div>
    <?= TbForm::checkboxes($send_checkboxes, 'Запрос адресован сервисам'); ?>
    <?= TbForm::actions('save'); ?>
</fieldset>
<?= FORM::close(); ?>
