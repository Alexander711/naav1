<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/service_items')
         ->set('current_url', 'admin/service/vacancy');
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Компания</th>
            <th>Заголовок</th>
            <th>Дата</th>
            <th>Статус</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($vacancy->order_by('date', 'DESC')->find_all() as $v): ?>
        <tr>
            <td><?= $v->service->name; ?></td>
            <td><?= $v->title; ?></td>
            <td><?= MyDate::show($v->date, TRUE); ?></td>
            <td><?= HTML::activate_checker($v->id, $v->active, 'admin/service/vacancy'); ?></td>
            <td><?= HTML::edit_button($v->id, 'admin/service/vacancy').HTML::delete_button($v->id, 'admin/service/vacancy'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>