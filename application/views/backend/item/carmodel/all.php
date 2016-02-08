<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?= Message::render(); ?>
<p align="right"><?= HTML::anchor('admin/item/carmodel/add', 'Добавить модель авто'); ?></p>
<table class="bordered-table zebra-striped">
    <thead>
        <tr class="title">
            <th>Название модели</th>
            <th>Марка авто</th>
            <th style="width: 45px;">Операции</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($model->find_all() as $c): ?>
        <tr>
            <td><?= $c->name; ?></td>
            <td><?= $c->car->name; ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/item/carmodel'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>