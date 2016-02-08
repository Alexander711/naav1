<?php
defined('SYSPATH') or die('No direct script access.');
$car_brands = array('0' => 'Выбрать марку автомобиля') + $car_brands;
$car_models = array('0' => 'Выбрать модель автомобиля') + $car_models;
$selected_works = Arr::get($values, 'work', array());
if ($service->name)
{
    echo FORM::open('messages/add?service='.$service->id, array('enctype' => 'multipart/form-data'));
}
else
{
    echo FORM::open('messages/add', array('enctype' => 'multipart/form-data'));
}
//echo Debug::vars($errors);
?>
<fieldset>
    <legend>Параметры автомобиля</legend>
    <ul class="form hvv" style="margin-left: 15px;">
        <li>
            <label for="add_qa_car_brand">Марка автомобиля</label>
            <?= FORM::select('car_id', $car_brands, Arr::get($values, 'car_id'), array('id' => 'add_qa_car_brand', 'style' => 'width: 235px;')); ?>
        </li>
        <li class="divider"></li>
        <li>
            <label for="add_qa_models">Модель автомобиля</label>
            <?= FORM::select('model_id', $car_models, Arr::get($values, 'model_id'), array('id' => 'add_qa_models', 'style' => 'width: 235px;')); ?>
        </li>
    </ul>
    <ul class="form hvv" style="margin-left: 15px;">
        <li>
            <label for="engine-volume"  style="width: 100px;">Объем двигателя</label>
            <?= FORM::input('volume', Arr::get($values, 'volume'), array('style' => 'width: 100px;', 'id' => 'engine-volume')); ?>
        </li>
        <li>
            <label style="width: 100px;">Коробка передач</label>
            <?php
            foreach ($gearboxes as $id => $name)
            {
                $selected = (Arr::get($values, 'gearbox_id', 1) == $id) ? TRUE : FALSE;
                echo '<label style="width: 55px; float: left;">'.FORM::radio('gearbox_id', $id, $selected).$name.'</label>';
            }
            ?>
        </li>
        <li class="divider"></li>
        <li>
            <label style="width: 75px;" for="vehicle-year">Год выпуска</label>
            <?= FORM::input('year', Arr::get($values, 'year'), array('id' => 'vehicle-year', 'style' => 'width: 75px;')); ?>
        </li>
        <li>
            <label style="width: 135px;" for="vin">VIN</label>
            <?= FORM::input('vin', Arr::get($values, 'vin'), array('id' => 'vin', 'style' => 'width: 135px;')); ?>
        </li>
    </ul>
    <ul class="form">
        <li>
            <label for="works">Перечень нужных работ</label>
            <select id="works" multiple="multiple" name="work[]" style="width: 320px;">
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
            <label for="question">Вопрос</label>
            <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'question', 'style' => 'width: 314px;')); ?>
        </li>
        <li>
            <label for="photo">Фото</label>
            <?= FORM::file('image', array('id' => 'photo')); ?>
        </li>
    </ul>
</fieldset>
<fieldset>
    <legend>Контактные данные</legend>
    <ul class="form">
        <li>
            <label for="contact">Контактное лицо:</label>
            <?= FORM::input('contact', Arr::get($values, 'contact'), array('id' => 'contact', 'style' => 'width: 207px;')); ?>
        </li>
        <li>
            <label for="email">Email:</label>
            <?= FORM::input('email', Arr::get($values, 'email'), array('id' => 'email', 'style' => 'width: 207px;')); ?>
        </li>
        <li>
            <label id="phone">Телефон:</label>
            <?= FORM::input('phone', Arr::get($values, 'phone'), array('id' => 'phone', 'style' => 'width: 207px;')); ?>
        </li>
        <li>
            <label id="city">Город:</label>
            <?= FORM::select('city_id', $cities, Arr::get($values, 'city_id'), array('id' => 'city', 'style' => 'width: 214px;')); ?>
        </li>
    </ul>
</fieldset>
<fieldset>
    <legend>Запрос адресован сервисам</legend>
    <ul class="form">
        <?php if ($service->name): ?>
            <li><label style="width: 300px;"><?= FORM::checkbox('', 0,TRUE) ?><?= ($service->type == 1) ? 'Автосервису' : 'Магазину автозапчастей'; ?> <?= $service->name; ?></label></li>
	    <?php endif; ?>
            <li><label style="width: 300px;"><?= FORM::checkbox('for_service_has_car', 1, (bool) Arr::get($values, 'for_service_has_car')) ?> Предоставлящим ремонт указанной модели автомобиля</label></li>
            <li><label style="width: 300px;"><?= FORM::checkbox('for_service_address', 1, (bool) Arr::get($values, 'for_service_address')) ?> Только из моего города</label></li>

         <li><?= FORM::submit(NULL, __('f_send'), array('class' => 'submit')); ?></li>
    </ul>
</fieldset>
<?= FORM::close(); ?>