<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p><?= HTML::anchor('admin/item/city/add', 'Добавить город', array('class' => 'btn btn-primary')); ?></p>
<table class="table table-bordered table-striped">
    <thead>
    <th>Название</th>
    <th>Название в род. падеже</th>
    <th>Кол-во компаний <acronym title="количество компаний из данного города">что это</acronym></th>
    <th></th>
    </thead>
    <tbody>
    <?php foreach ($city->find_all() as $c): ?>
        <tr>
            <td><?= $c->name; ?></td>
            <td><?= $c->genitive_name; ?></td>
            <td><?= count($c->services->find_all()); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/item/city'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>