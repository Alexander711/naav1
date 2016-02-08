<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/news')
         ->set('current_url', 'admin/news/world');
?>

<div style="margin-bottom: 10px;" class="clearfix">
    <?= HTML::anchor('admin/news/world/add', 'Добавить новость автомира', array('class' => 'btn btn-primary')); ?>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 450px;">Заголовок</th>
            <th>Дата</th>
            <th>Статус</th>
            <th style="width: 72px;">Операции</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($news->order_by('date', 'DESC')->find_all() as $c): ?>
        <tr>
            <td><?= $c->title; ?></td>
            <td><?= MyDate::show($c->date, TRUE); ?></td>
            <td><?= HTML::activate_checker($c->id, $c->active, 'admin/news/world') ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/news/world').' '.HTML::delete_button($c->id, 'admin/news/world'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>