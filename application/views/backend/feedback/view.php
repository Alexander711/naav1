<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h3><?= ($feedback->type == 2) ? 'Заявка на рекламу' : 'Запрос' ?> от пользователя <?= $feedback->user->username; ?></h3>
<div class="row-fluid">
    <table class="feedback_table">
        <tr>
            <td>Пользователь</td>
            <td><?= $feedback->user->username; ?></td>
        </tr>
        <tr>
            <td>Заголовок</td>
            <td><?= $feedback->title; ?></td>
        </tr>
        <tr>
            <td>Текст запроса</td>
            <td><?= $feedback->text; ?></td>
        </tr>
        <?php if ($feedback->type == 2): ?>
            <tr>
                <td>Компания</td>
                <td><?= ($feedback->service_id == 0) ? 'Не выбрана пользователем' : $feedback->service->name; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td>&nbsp;</td>
            <td>
                <?= HTML::anchor('admin/notice/add_user/'.$feedback->user->id, 'Отправить уведомление', array('class' => 'btn btn-large btn-primary')) ?>
                <?= HTML::anchor('admin/email/send?email='.$feedback->user->email, 'Отправить Email', array('class' => 'btn btn-large btn-primary')) ?>
            </td>
        </tr>
    </table>
</div>