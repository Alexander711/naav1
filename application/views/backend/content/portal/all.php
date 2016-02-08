<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/content')
         ->set('current_url', 'admin/content/portal');
?>

<div class="clearfix" style="margin-bottom: 10px;">
    <?= HTML::anchor('admin/content/portal/add', 'Добавить страницу', array('class' => 'btn btn-primary')); ?>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr class="title">
            <th style="width: 200px;">Заголовок</th>
            <th>Адрес</th>
            <th>Дата создания</th>
            <th>Статус</th>
            <th style="width: 85px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($content->find_all() as $c): ?>
        <tr>
            <td><?= (mb_strlen($c->title) > 0) ? $c->title : 'Заголовок отсутствует'; ?></td>
            <td><?= HTML::anchor($c->url, $c->url, array('target' => '_blank')); ?></td>
            <td><?= MyDate::show($c->date, TRUE); ?></td>
            <td><?= HTML::activate_checker($c->id, $c->active, 'admin/content/portal'); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/content/portal').' '.HTML::delete_button($c->id, 'admin/content/portal'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>