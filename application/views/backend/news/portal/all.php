<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/news')
         ->set('current_url', 'admin/news/portal');
?>

<div class="clearfix" style="margin-bottom: 10px;">
    <?= HTML::anchor('admin/news/portal/add', 'Добавить новость Ассоциации', array('class' => 'btn btn-primary')); ?>
</div>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 450px;">Заголовок</th>
            <th>Дата</th>
            <th>Статус</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($news->order_by('date', 'DESC')->find_all() as $c): ?>
        <tr>
            <td><?= $c->title; ?></td>
            <td><?= MyDate::show($c->date, TRUE); ?></td>
            <td><?= HTML::activate_checker($c->id, $c->active, 'admin/news/portal'); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/news/portal'); ?><span class="divider">  </span><?= HTML::delete_button($c->id, 'admin/news/portal'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>