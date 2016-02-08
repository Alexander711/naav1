<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/news')
         ->set('current_url', 'admin/news/service');
?>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Компания</th>
            <th style="width: 300px;">Заголовок</th>
            <th>Дата</th>
            <th>Статус</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($news->order_by('date_create', 'DESC')->find_all() as $c): ?>
        <tr>
            <td><?= $c->service->name; ?></td>
            <td><?= $c->title; ?></td>
            <td><?= MyDate::show($c->date_create, TRUE); ?></td>
            <td><?= HTML::activate_checker($c->id, $c->active, 'admin/news/service'); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/news/service').' '.HTML::delete_button($c->id, 'admin/news/service') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>