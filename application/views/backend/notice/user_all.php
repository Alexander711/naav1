<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/notice')
         ->set('current_url', 'admin/notice/user');
?>
<div style="margin-bottom: 10px;" class="clearfix">
    <?= HTML::anchor('admin/notice/add_user', 'Добавить уведомление для пользователей', array('class' => 'btn btn-primary')); ?>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 450px;">Текст</th>
            <th>Дата</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($notice->find_all() as $c): ?>
        <tr>
            <td><?= Text::limit_words(strip_tags($c->text), 120); ?></td>
            <td><?= MyDate::show($c->date, TRUE); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/notice/edit_user', TRUE).' '.HTML::delete_button($c->id, 'admin/notice') ; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>