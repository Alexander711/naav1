<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<div id="tab">
    <ul>
        <li><?= HTML::anchor('#', 'Уведомления', array('class' => 'active')); ?></li>
        <li><?= HTML::anchor('admin/notice/system', 'Системные уведомления'); ?></li>
    </ul>
</div>
<?= Message::render(); ?>
<div style="text-align: right;"><?= HTML::anchor('admin/notice/add', 'Добавить уведомление'); ?></div>
<table class="content_table">
    <tr class="title">
        <td style="width: 450px;">Текст</td>
        <td>Дата</td>
        <td>Операции</td>
    </tr>
    <?php foreach ($notice->order_by('date', 'DESC')->find_all() as $c): ?>
        <tr>
            <td style="font-weight: bold;"><?= Text::limit_words(strip_tags($c->text), 120); ?></td>
            <td><?= $c->date; ?></td>
            <td><?= HTML::anchor('admin/notice/edit/'.$c->id, 'Редактировать'); ?> |  <?= HTML::anchor('admin/notice/delete/'.$c->id, 'Удалить'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>