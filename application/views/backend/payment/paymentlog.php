<?php
defined('SYSPATH') or die('No direct script access.');

?>
<?php echo $pagination; ?>
<table class="table table-striped table-bordered table-hover" style="text-align: center;">
	<thead>
	<tr>
		<th>ID</th>
		<th style="width: 300px;">Пользователь</th>
		<th>Старое значение</th>
		<th>Новое значение</th>
		<th>Дата</th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($log->order_by('id', 'DESC')->find_all() as $s): ?>
	<tr>
		<td><?= $s->id; ?></td>
		<td> <?= HTML::anchor('admin/users/edit_info/'.$s->user_id, $s->user->username)?></td>
		<td><?= $s->old_value; ?></td>
		<td><?= $s->new_value; ?></td>
		<td><?= Date::formatted_time($s->cdate,'d.m.Y H:i'); ?></td>
	</tr>
		<?php endforeach; ?>
	</tbody>

</table>
<?php echo $pagination; ?>

