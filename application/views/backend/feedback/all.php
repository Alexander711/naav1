<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/feedback')
         ->set('current_url', Request::current()->uri())
?>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Заголовок</th>
        <th>Пользователь</th>
        <th>Дата</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($feedback->find_all() as $f): ?>
        <tr>
            <td>
                <?= $f->title; ?>
            </td>
            <td>
                <?= $f->user->username; ?>
            </td>
            <td>
                <?= MyDate::show($f->date, TRUE); ?>
            </td>
            <td>
                <div class="btn-group">
                    <?= HTML::anchor('admin/feedback/view/'.$f->id, '<i class="icon-zoom-in"></i>', array('class' => 'btn btn-small')) ?>
                    <?= HTML::anchor('#', '<i class="icon-envelope"></i> Ответить <span class="caret"></span>', array('class' => 'btn btn-small dropdown-toggle', 'data-toggle' => 'dropdown')) ?>
                    <ul class="dropdown-menu">
                        <li><?= HTML::anchor('admin/notice/add_user/'.$f->user->id, 'Отправить уведомление'); ?></li>
                        <li><?= HTML::anchor('admin/email/send?email='.$f->user->email, 'Отправить Email'); ?></li>

                    </ul>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>