<?php
defined('SYSPATH') or die('No direct script access.');

$statuses = array("N"=>"Новый","P"=>"Оплачен","C"=>"Отменен","E"=>"Просрочен");

?>
<table class="table table-striped table-bordered table-hover" style="text-align: center;">
	<thead>
	<tr>
		<th>ID</th>
		<th>Пользователь</th>
		<th>Платежная система</th>
		<th>Сумма</th>
		<th>Кол-во дней</th>
		<th>Создан</th>
		<th>Изменен</th>
		<th style="width: 100px;">Статус</th>
		<th style="width: 150px;">Вступительный взнос</th>
		<th style="width: 150px;">Проводка</th>
		<th style="width: 72px;"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($invoice->order_by('id', 'DESC')->find_all() as $s): ?>
	<tr>
		<td><?= $s->id; ?></td>
		<td><a href="/admin/users/edit_info/<?= $s->user->id; ?>"><?= $s->user->username; ?></a></td>
		<td><?= $s->payment->payment_name; ?></td>
		<td><?= $s->amount; ?></td>
		<td><?= $s->days_amount; ?></td>
		<td><?= Date::formatted_time($s->create_date,'d.m.Y H:i'); ?></td>
        <td><?= Date::formatted_time($s->modify_date,'d.m.Y H:i'); ?></td>
		<td><?
			$class="";
			switch ($s->status){
				case "N":
					$class="label-info";
					break;
				case "P":
					$class="label-success";
					break;
				case "E":
					$class="label-warning";
					break;

			}

			echo "<span class=\"label ".$class."\">".$statuses[$s->status]."</span>";

			?></td>
		<td><?= $s->entrance_fee == 'Y' ? '<i class="icon-ok"></i>':""; ?></td>
		<td>
			<?
			if ($s->status == 'N') {
				echo '<a class="btn" title="Провести" href="/admin/payment/invoice/process/'.$s->id.'">Провести платеж <i class="icon-play"></i></a>';
			}
			?>

		</td><td>

			<?= HTML::edit_button($s->id, 'admin/payment/invoice') ?>
		</td>
	</tr>
		<?php endforeach; ?>
	</tbody>

</table>

