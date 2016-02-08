<?php
defined('SYSPATH') or die('No direct script access.');

?>
<?php echo $pagination; ?>
<table class="table table-striped table-bordered table-hover" style="text-align: center;">
	<thead>
	<tr>
		<th>ID</th>
		<th style="width: 300px;">Платеж</th>
		<th>Статус</th>
		<th>Контент</th>
		<th>Дата</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($invoice_log->order_by('id', 'DESC')->find_all() as $s): ?>
	<tr>
		<td><?= $s->id; ?></td>
		<td>UPI - <?= $s->invoice_id; ?></td>
		<td><?= $s->status; ?></td>
		<td><?= nl2br($s->content); ?></td>
		<td><?= Date::formatted_time($s->cdate,'d.m.Y H:i'); ?></td>
	</tr>
		<?php endforeach; ?>
	</tbody>

</table>
<?php echo $pagination; ?>

