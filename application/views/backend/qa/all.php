<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Сервис</th>
            <th>Марка авто</th>
            <th>Имя / Email</th>
            <th>Дата</th>
            <th>Статус</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($qa->order_by('date', 'DESC')->find_all() as $q):
	        $service_name = "";
	        $i = 0;
	        foreach ($q->services->find_all() as $s) {
		        $service_name .= "$s->name\n";
		        $i++;
	        }
	    ?>



            <tr>
                <td><?
	                if ($i == 0)
		                echo "";
	                elseif ($i == 1)
		                echo $service_name;
	                else {
		                echo '<a href="#service-'.$q->id.'" data-toggle="collapse" data-target="#service-'.$q->id.'">Много сервисов ('.$i.')</a>';
		                echo '<div id="service-'.$q->id.'" class="collapse">'. nl2br($service_name).'</div>';
	                }


	                ?></td>
                <td><?= $q->carbrand->name.' '.$q->model->name ?></td>
                <td><?= $q->contact. ' / '.HTML::mailto($q->email); ?></td>
                <td><?= MyDate::show($q->date, TRUE); ?></td>
                <td><?= HTML::activate_checker($q->id, $q->active, 'admin/service/qa'); ?></td>
                <td><?= HTML::edit_button($q->id, 'admin/service/qa').HTML::delete_button($q->id, 'admin/service/qa'); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>

</table>