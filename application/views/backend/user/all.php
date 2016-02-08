<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?= Message::render(); ?>
<div style="text-align: right;"><?= HTML::anchor('admin/users/add', 'Добавить пользователя'); ?></div>
<?php echo $pagination; ?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 150px;">Имя пользователя</th>
            <th>Email</th>
            <th>Статус</th>
            <th>Кол-во компаний</th>
            <th>Дата регис. / послед. входа</th>
            <th>Действителен до</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($user->find_all() as $u): ?>
        <?php $company_count = count($u->services->find_all()); ?>
        <tr>
            <td><?= $u->username; ?></td>
            <td><?= HTML::anchor('admin/email/send?email='.$u->email, $u->email); ?></td>
            <td>
                <?php if ($u->has('roles', ORM::factory('role')->where('name', '=', 'login')->find())): ?>
                    Активен
                    <?php if ($u->has('roles', ORM::factory('role')->where('name', '=', 'admin')->find())): ?>
                        <?= ' (админ)' ?>
                    <?php endif; ?>

                    <?= HTML::anchor('admin/users/deactivate/'.$u->id, HTML::image('assets/img/admin/deactive.png')); ?>
                <?php else: ?>
                    Неактивен <?= HTML::anchor('admin/users/activate/'.$u->id, HTML::image('assets/img/admin/active.png')); ?>
                <?php endif; ?>
            </td>
            <td><?= ($company_count > 0) ? $company_count.HTML::anchor('admin/users/services/'.$u->id, ' просм.') : 'Не имеет компаний'; ?></td>
            <td><?= MyDate::show_small($u->date_create); ?> / <?= ($u->last_login != NULL) ? date('d.m.Y H:i', $u->last_login) : 'ни разу'; ?></td>
            <td><?= ($u->expires != NULL) ? Date::formatted_time($u->expires,'d.m.Y') : ''; ?></td>
            <td>
                <div class="btn-group">
                    <?= HTML::edit_button($u->id, 'admin/users/edit_info', TRUE).HTML::delete_button($u->id, 'admin/users'); ?>
                </div>

            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>

</table>
<?php echo $pagination; ?>


