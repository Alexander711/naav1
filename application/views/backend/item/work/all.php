<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<p><?= HTML::anchor('admin/item/work/add', 'Добавление услуги', array('class' => 'btn btn-primary')); ?></p>
<table class="table table-bordered table-striped">
    <thead>
    <th>Название</th>
    <th>Кол-во сервисов <acronym title="количество автосервисов предоставляющих данную услугу">что это</acronym></th>
    <th>Дата редактирования <acronym title="Дата редактирования страницы поиска по данной услуге">что это</acronym></th>
    <th style="width: 280px;"></th>
    </thead>
    <tbody>
    <?php foreach ($work->find_all() as $w): ?>
        <tr>
            <td><?= $w->name; ?></td>
            <td><?= count($w->services->find_all()); ?></td>
            <td>
                <div class="btn-group">
                    <a href="#" class="btn dropdown-toggle" data-toggle="dropdown">Даты редактирования <i class="caret"></i></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($w->contents->find_all() as $c): ?>
                            <li><?= HTML::anchor('#'.$c->id, ($c->date_edited != '0000-00-00 00:00:00') ? Date::full_date($c->date_edited, TRUE) : 'ни разу', array('title' => $c->city->name)); ?></li>
                        <?php endforeach;?>
                    </ul>

                </div>
            </td>
            <td>
                <div class="btn-group">
                    <?= HTML::anchor('admin/item/work/edit/'.$w->id, '<i class="icon-pencil"></i>', array('class' => 'btn', 'style' => 'z-index: 1;')).HTML::anchor('admin/item/work/delete/'.$w->id, '<i class="icon-remove"></i>', array('class' => 'btn', 'style' => 'z-index: 1;')) ?>
                    <div class="btn-group">

                        <a href="#" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-pencil"></i>Редактировать страницу <i class="caret"></i></a>
                        <ul class="dropdown-menu" style="left: 70px;">
                            <?php foreach ($w->contents->find_all() as $c): ?>
                                <li><?= HTML::anchor('admin/content/works/edit/'.$c->id, $c->city->name); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>