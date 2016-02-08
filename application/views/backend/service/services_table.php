<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr class="title">
            <th style="width: 270px;">Название</th>
            <th>Авто / Услуг</th>
            <th>Нов. / <strong style="color: #628ab4; font-weight: normal;">Вак.</strong> / <strong style="color: #8a1f11; font-weight: normal;">Акц.</strong> / <strong style="color: #009900; font-weight: normal;">Отз.</strong></th>
            <th style="width: 230px;">Дата созд. <sup><a rel="tooltip" href="#" data-original-title="Дата создания: дата регистрации сервиса или магазина на сайте, может иметь значение неизвестно для созданных в старой версии сайта, т.к. в старой версии не отслеживалась дата создания">1</a></sup>/ Ред. <sup><a rel="tooltip" href="#" data-original-title="Дата редактирования: дата изменения информации о сервисе или магазине, добавления новости, акции, вакансии. Данный параметр испольуется как сортировочный в выборке на сайте">2</a></sup>/ Посл. вх. <sup><a rel="tooltip" href="#" data-original-title="Дата последнего входа: дата последнего логина пользователя которому принадлежит сервис или магазин">3</a></sup></th>
            <th>Статус</th>
            <th style="width: 135px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($service->find_all() as $s): ?>
        <tr>
            <td><?= $s->name ?></td>
            <td><?= count($s->cars->find_all()) ?> / <?= count($s->works->find_all()) ?></td>
            <td><?= HTML::anchor('admin/services/news/'.$s->id, count($s->news->find_all()), array('style' => 'color: #999999;')) ?> / <?= HTML::anchor('admin/services/vacancies/'.$s->id, count($s->vacancies->find_all()), array('style' => 'color: #628ab4'))  ?> / <?= HTML::anchor('admin/services/stocks/'.$s->id, count($s->stocks->find_all()), array('style' => 'color: #8a1f11;')); ?> / <?= HTML::anchor('admin/services/reviews/'.$s->id, count($s->reviews->find_all()), array('style' => 'color: #009900;'));  ?></td>
            <td><?= ($s->date_create == '0000-00-00 00:00:00') ? 'Неизвестно' : MyDate::show_small($s->date_create); ?>
                /
                <?= ($s->date_edited == '0000-00-00 00:00:00' ? 'Ни разу' : MyDate::show_small($s->date_edited)); ?>
                /
                <?= ($s->user->last_login != NULL) ? date('d.m.Y', $s->user->last_login) : 'ни разу'; ?>
            </td>
            <td><?= HTML::activate_checker($s->id, $s->active, 'admin/services'); ?></td>
            <td>
                <?= HTML::edit_button($s->id, 'admin/services').' '.HTML::delete_button($s->id, 'admin/services').' '.HTML::add_notice($s->id); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>