<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/service_items')
         ->set('current_url', 'admin/service/review');
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr class="title">
            <th>Компания</th>
            <th>Email (и если залогинен имя пользователя)</th>
            <th>Дата</th>
            <th>Статус</th>
            <th style="width: 72px;">Операции</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($review->order_by('date', 'DESC')->find_all() as $c): ?>
        <tr>
            <td><?= $c->service->name; ?></td>
            <td><?= $c->email.' <strong>'.$c->user->username.'</strong>'; ?></td>
            <td><?= MyDate::show($c->date); ?></td>
            <td><?= HTML::activate_checker($c->id, $c->active, 'admin/service/review'); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/service/review').HTML::delete_button($c->id, 'admin/service/review'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>