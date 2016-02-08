<?php
defined('SYSPATH') or die('No direct script access.');

?>
<table class="table table-striped table-bordered table-hover" style="text-align: center;">
	<thead>
	<tr>
		<th>ID</th>
		<th>Название</th>
		<th style="width: 100px;">Сортировка</th>
		<th style="width: 100px;">Статус</th>
		<th style="width: 72px;"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($payment->order_by('id', 'ASC')->find_all() as $s): ?>
	<tr>
		<td><?= $s->id; ?></td>
		<td><?= $s->payment_name; ?></td>
		<td><?= $s->position; ?></td>
		<td><?= $s->status == 'Y' ? '<i class="icon-ok"></i>':""; ?></td>
		<td><?= HTML::edit_button($s->id, 'admin/payment/main') ?></td>
	</tr>
		<?php endforeach; ?>
	</tbody>

</table>

