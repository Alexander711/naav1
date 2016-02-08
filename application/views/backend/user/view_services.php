<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?= Message::render(); ?>
<div style="text-align: right;"></div>
<table class="bordered-table zebra-striped">
    
    <tr>
        <td style="width: 150px;">Название компании</td>
        <td>Статус</td>
        <td>Дата создания</td>
        <td>Операции</td>
    </tr>
    <?php foreach ($service->find_all() as $c): ?>
        <tr>
            <td><?= $c->name ?></td>
            <td><?= ($c->active == 1) ? 'Активен '.HTML::anchor('admin/services/deactivate/'.$c->id, 'Деактивировать') : 'Неактивен '.HTML::anchor('admin/services/activate/'.$c->id, 'Активировать'); ?></td>
            <td><?= $c->date; ?> </td>
            <td><?= HTML::anchor('admin/services/edit/'.$c->id, 'Редактировать'); ?> |  <?= HTML::anchor('admin/services/delete/'.$c->id, 'Удалить'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
