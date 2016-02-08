<?php
defined('SYSPATH') or die('No direct script access.');
$selected_services = Arr::get($values, 'service', array());
$selected_services_city = Arr::get($values, 'service_city', array());
//echo Debug::vars(Arr::get($debug, 'new'));
echo FORM::open($url, array('class' => 'form-horizontal'));
?>
<?php if (Request::current()->action() == 'edit'): ?>
        <p style="font-size: 14px; font-weight: bold;">Внимание, при редактировании уведомление будет иметь статус непрочитанного у тех кто прочел</p>
<?php endif ?>

<h3></h3>
<fieldset>
    <legend>Параметры уведомления</legend>
	<div class="control-group">
        <label class="control-label">Город</label>
        <div class="controls">
            <select id="services_city" multiple="multiple" name="services_city[]" style="width: 400px;">
                <?php foreach ($city_list as $city_id => $value): ?>
                    <?php $attr = (in_array($city_id, $selected_services_city)) ? array('selected' => 'selected') : array(); ?>
                    <option <?= HTML::attributes($attr); ?> value="<?= $value['list'] ?>"><?= $value['city_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <div id="selected_services_city"></div>
        </div>
    </div>

	<div class="control-group">
        <label class="control-label">Кому</label>
        <div class="controls">
            <select id="services" multiple="multiple" name="service[]" style="width: 400px;">
                <?php foreach ($services as $service_id => $value): ?>
                    <?php $attr = (in_array($service_id, $selected_services)) ? array('selected' => 'selected') : array(); ?>
                    <option <?= HTML::attributes($attr); ?> value="<?= $service_id ?>"><?= $value['service_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <p class="help-block">Кому адресовано уведомление  (Не выбрав никого отправиться всем)</p>
            <div id="selected_services"></div>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Заголовок</label>
        <div class="controls">
            <?= FORM::input('title', Arr::get($values, 'title'), array('class' => 'span7')); ?>
        </div>
    </div>

        <?php if (isset($mode) AND $mode == 'edit'): ?>
            <div class="control-group">
                <label class="control-label">Прочее</label>
                <div class="controls">
                    <label class="checkbox">
                        <?= FORM::checkbox('save_status', 1, (bool) Arr::get($values, 'save_status', TRUE)) ?>
                        Сохранить статусы прочитан/непрочитан для уже отправленных
                    </label>
                    <p class="help-block">Если убрать галочку уведомление будет иметь статус непрочитан у всех<br />Всем будут по новой отправленна рассылка на почту</p>
                </div>
            </div>
        <?php endif; ?>

</fieldset>
<fieldset>
    <legend>Текст уведомления</legend>
    <div class="clearfix">
        <?= FORM::textarea('text', Arr::get($values, 'text'), array('id' => 'text')); ?>
        <?php if (Arr::get($errors, 'text', NULL)): ?>
            <p class="help-block"><?= $errors['text']; ?></p>
        <?php endif; ?>
    </div>

</fieldset>
<?= FORM::submit(NULL, 'Сохранить', array('class' => 'btn btn-large btn-success')); ?>
<?= FORM::close(); ?>

<script>
	function checkByValue(value){
		$("#services").multiselect("widget").find(":checkbox[value='"+value+"']").each(function(){
		    this.click();
		});
	}


</script>