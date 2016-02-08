<?php
defined('SYSPATH') or die('No direct script access.');
?>
<?=
View::factory('backend/navigation/user_edit')
		->set('current_url', Request::current()->url())
		->set('user_id', $user_id)
		->render()
;

?>
<?= FORM::open(Request::current()->uri() . Request::current()->query('edit'), array('autocomplete' => 'off', 'class' => 'form-horizontal')) ?>
<fieldset>
	<legend><?= $title; ?></legend>
	<div class="control-group">
		<label class="control-label">Имя пользователя</label>

		<div class="controls">
			<?= FORM::input('username', Arr::get($values, 'username')); ?>
			<p class="help-block" style="color: red;"><?= Arr::get($errors, 'username'); ?></p>
		</div>

	</div>
	<div class="control-group">
		<label class="control-label">Email</label>

		<div class="controls">
			<?= FORM::input('email', Arr::get($values, 'email')); ?>
			<p class="help-block" style="color: red;"><?= Arr::get($errors, 'email'); ?></p>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Администратор</label>

		<div class="controls">
			<label><?= Form::checkbox('is_admin', null, Arr::get($values, 'is_admin', 0) == 0 ? false : true, array('id' => 'is_admin',)); ?></label>

			<p class="help-block" style="color: red;"><?= Arr::path($errors, 'is_admin'); ?></p>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Действителен до</label>

		<div class="controls">
			<div class="input-append date" data-date="<?=Arr::get($values, 'expires', null)?>"
			     data-date-format="dd.mm.yyyy">
				<input class="datepicker" size="16" type="text" name="expires"
				       value="<?=Date::formatted_time(Arr::get($values, 'expires', null),'d.m.Y')?>">
				<span class="add-on"><i class="icon-th"></i></span>
			</div>
			<p class="help-block" style="color: red;"><?= Arr::path($errors, 'expires'); ?></p>

		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Тип пользователя</label>

		<div class="controls">
			<?php echo Form::select("user_type",array("disabled" => "Отключен", "user" => "Пользователь", "service" => "Автосервис"),Arr::get($values, 'user_type', "disabled")) ?>
			<p class="help-block" style="color: red;"><?= Arr::path($errors, 'user_type'); ?></p>

		</div>
	</div>
	<div class="form-actions">
		<?= FORM::submit(null, 'Сохранить', array('class' => 'btn btn-success btn-large')); ?>
	</div>
</fieldset>
<?= FORM::close()
; ?>
<script>
	$('.datepicker').datepicker({format:'dd.mm.yyyy'});
</script>
    