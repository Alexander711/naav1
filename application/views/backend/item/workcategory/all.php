<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?= Message::render(); ?>
<p><?= HTML::anchor('admin/item/workcategory/add', 'Добавить категорию услуг', array('class' => 'btn btn-primary')); ?></p>
<table class="table table-bordered table-striped">
    <thead>
    <th>Название</th>
    <th>Кол-во услуг в категории</th>
    <th></th>
    </thead>
    <tbody>
    <?php foreach ($category->find_all() as $c): ?>
        <tr>
            <td><?= $c->name; ?></td>
            <td><?= count($c->works->find_all()); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/item/workcategory').' '.HTML::delete_button($c->id, 'admin/item/workcategory'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>