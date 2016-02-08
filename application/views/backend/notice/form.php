<?php
defined('SYSPATH') or die('No direct script access.');
$selected_services = Arr::get($values, 'service', array());
echo Message::show_errors($errors);
echo FORM::open($url);
?>
<?php if (Request::current()->action() == 'edit'): ?>
        <p style="font-size: 14px; font-weight: bold;">Внимание, при редактировании уведомление будет иметь статус непрочитанного у тех кто прочел</p>
<?php endif ?>
<fieldset style="width: 450px">
    <legend>Параметры уведомления</legend>
    <p>Кому адресовано уведомление  (Не выбрав никого отправиться всем)</p>
    <select id="services" multiple="multiple" name="service[]" style="width: 400px;">
        <?php foreach ($service->find_all() as $service): ?>
            <?php
            $selected = '';
            if (in_array($service->id, $selected_services))
            {
                $selected = 'selected = "selected"';
            }
            ?>
            <option value="<?= $service->id ?>" <?= $selected ?>><?= $service->name; ?></option>
        <?php endforeach; ?>
    </select>
    <div id="selected_services"></div>
    <p>Заголовок</p>
    <?= FORM::input('title', Arr::get($values, 'title')); ?>
</fieldset>

<fieldset>
    <legend>Текст уведомления</legend>
    <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')); ?>
</fieldset>
<?= FORM::submit(NULL, 'Сохранить', array('class' => 'submit')); ?>
<?= FORM::close(); ?>