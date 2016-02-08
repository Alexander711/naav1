<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p><?= HTML::anchor('admin/item/district/add', 'Добавить округ', array('class' => 'btn btn-primary')) ?></p>
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
    <?php foreach ($district->find_all() as $d): ?>
        <tr>
            <td><?= $d->name; ?></td>
            <td><?= $d->city->name; ?></td>
            <td><?= count($d->services->find_all()); ?></td>
            <td><?= HTML::edit_button($d->id, 'admin/item/district').' '.HTML::anchor('admin/content/district/edit/'.$d->content->id, 'Редактировать страницу', array('class' => 'btn')).' '.HTML::delete_button($d->id, 'admin/item/district'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>