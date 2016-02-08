<?php
defined('SYSPATH') or die('No direct script access.');

$statuses = array("N"=>"Новый","P"=>"Оплачен","C"=>"Отменен","E"=>"Просрочен");

?>

<br>
<legend>Оплата платежа <strong>UPI - <?= $invoice->id; ?> <?php echo  $invoice->entrance_fee == 'Y' ? "(вступительный взнос)":""; ?></strong></legend>
<?php if ($invoice->status == "N") { ?>
<div style="float:right;display: inline-block">
	<a class="btn btn-danger" href="/cabinet/payment/cancel/<?= $invoice->id; ?>">Отменить платеж</a>
</div>

<?php } ?>

	<br>
	<br>





<table class="btable">
	<tbody>
		<tr>
			<td>
				Оплата <strong><?= $invoice->amount; ?> руб.</strong> по счету <strong>UPI - <?= $invoice->id; ?></strong><br>
				Платежная система: <strong><?= $paymentSystem->payment_name; ?></strong><br>
				Статус: <strong><?= $statuses[$invoice->status]; ?></strong>
				<br>
				<br>
				<?php echo $invoice->status == 'N' ? $paymentSystem->description : ""; ?>
			</td>
		</tr>
		<tr>
			<td align="center">
				<br>
				<?php
					if ($invoice->status == 'N') {
						try {
							echo $paymentForm;

						}catch (Exception $e) {

						}
					}
				?>
			</td>
		</tr>
	</tbody>
</table>