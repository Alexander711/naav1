<?php
defined('SYSPATH') or die('No direct script access.');


echo TbForm::open(NULL,array('class' => 'form-horizontal'), $values, $errors);
//echo FORM::open();
?>

<legend>Новый платеж</legend>
	<br>
	<br>
<table class="table table-condensed">
	<tr>
		<th></th>
		<th>Название</th>
		<th>Дней использования</th>
		<th>Цена</th>
	</tr>
	<?php

		$first_value = current($settings);
		$current_p_value = Arr::get($values, 'payment_value', $first_value['id'] );

		foreach ($settings as $id => $s) {
			$checked = ( $current_p_value == $id) ? TRUE : FALSE;

			?>
			<tr>
				<td><?= FORM::radio('payment_value', $id, $checked) ?></td>
				<td><?=$s['name']?></td>
				<td><?=$s['days']?></td>
				<td><?=$s['price']?></td>
			</tr>
			<?php
		}
	?>

</table>

<br>
<br>
<legend>Способ оплаты</legend>
<br>
<table class="table table-condensed">
	<?php


		$first_value = current($payments);
		$current_payment = Arr::get($values, 'payment_system', $first_value['id'] );

		foreach ($payments as $id => $p) {
			$checked = ( $current_payment == $id) ? TRUE : FALSE;

			?>
			<tr>
				<td><?= FORM::radio('payment_system', $id, $checked) ?></td>
				<td>
					<?=$p['payment_name']?>
					<br>
					<small class="muted"><?=$p['tips']?></small>
				</td>
				<td><?=$p['description']?></td>
			</tr>
			<?php
		}
	?>

</table>


<?= FORM::submit(null, 'Создать платеж', array('class' => 's_button')); ?>
<?= FORM::close(); ?>

