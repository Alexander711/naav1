<?php
defined('SYSPATH') or die('No direct script access.');

?>
<?= FORM::open(Request::current()->uri(), array('autocomplete' => 'off', 'class' => 'form-horizontal')) ?>
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
		<label class="control-label">Пароль</label>

		<div class="controls">
			<?= Form::password('password', null, array('class' => 's_inp', 'id' => 'form_password', 'data-typetoggle' => '#reg_pass_view')); ?>

			<label><?= Form::checkbox(null, null, false, array('id' => 'reg_pass_view',)); ?> Показать пароль</label>

			<p class="help-block" style="color: red;"><?= Arr::path($errors, 'password'); ?></p>
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
			<div class="input-append date" id="dp3" data-date="<?=Arr::get($values, 'expires', null)?>"
			     data-date-format="dd.mm.yyyy">
				<input class="datepicker" size="16" type="text" name="expires"
				       value="<?=Arr::get($values, 'expires', null)?>">
				<span class="add-on"><i class="icon-th"></i></span>
			</div>
			<p class="help-block" style="color: red;"><?= Arr::path($errors, 'is_admin'); ?></p>

		</div>
	</div>

	<div class="control-group">
		<label class="control-label">Тип пользователя</label>

		<div class="controls">
			<?php echo Form::select("user_type",array("disabled" => "Отключен", "user" => "Пользователь", "service" => "Автосервис"),"disabled") ?>
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
