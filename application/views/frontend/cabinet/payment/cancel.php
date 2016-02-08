<?php
defined('SYSPATH') or die('No direct script access.');

?>

<br>
<form method="POST" action="/cabinet/payment/cancel/<?= $id; ?>">
	<legend>Вы действительно хотите отменить платеж <strong>UPI - <?= $id; ?></strong></legend>
	<br />
	<div style="text-align: center;">
		<input type="submit" name="yes" value="Да" class="btn btn-success"/>
		<input type="submit" name="no" value="Нет" class="btn btn-danger"/>
		<input type="hidden" name="id" value="<?= $id; ?>"/>
	</div>


</form>