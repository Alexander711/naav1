<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Название</th>
        <th>Город</th>
        <th>Кол-во компаний <acronym title="">что это</acronym></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($metro->order_by('city_id', 'ASC')->order_by('name', 'ASC')->find_all() as $m): ?>
        <tr>
            <td><?= $m->name; ?></td>
            <td><?= $m->city->name; ?></td>
            <td><?= count($m->services->find_all()); ?></td>
            <td><?= HTML::edit_button($m->id, 'admin/item/metro').' '.HTML::anchor('admin/content/metro/edit/'.$m->content->id, 'Редактировать страницу', array('class' => 'btn')).' '.HTML::delete_button($m->id, 'admin/item/metro'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>