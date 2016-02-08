<?php
defined('SYSPATH') or die('No direct script access.');

?>
<div style="text-align: right;"><?= HTML::anchor('admin/payment/settings/edit', 'Добавить запись', array('class' => 'btn btn-primary')); ?></div>
<br/>
<table class="table table-striped table-bordered table-hover" style="text-align: center;">
	<thead>
	<tr>
		<th>ID</th>
		<th style="width: 300px;">Название</th>
		<th>Стоимость</th>
		<th>Дней</th>
		<th>Показывать в списке</th>
		<th>Статус</th>
		<th>Сортировка</th>
		<th>Системная</th>
		<th style="width: 72px;"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($settings->order_by('id', 'ASC')->find_all() as $s): ?>
	<tr>
		<td><?= $s->id; ?></td>
		<td><?= $s->name; ?></td>
		<td><?= $s->price; ?></td>
		<td><?= $s->days; ?></td>
		<td><?= $s->in_list == 'Y' ? '<i class="icon-ok"></i>':""; ?></td>
		<td><?= $s->status == 'Y' ? '<i class="icon-ok"></i>':""; ?></td>
		<td><?= $s->sort; ?></td>
		<td><?= $s->system == 'Y' ? '<i class="icon-ok"></i>':""; ?></td>
		<td><?= HTML::edit_button($s->id, 'admin/payment/settings') ?>
			<? echo $s->system == 'Y' ? "":HTML::delete_button($s->id, 'admin/payment/settings') ?></td>
	</tr>
		<?php endforeach; ?>
	</tbody>

</table>

