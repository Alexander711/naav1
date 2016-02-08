<?php
defined('SYSPATH') or die('No direct script access.');
$news->reset(FALSE);
?>
<div class="add_item"><?= HTML::anchor('cabinet/news/add', 'Добавить новость '.HTML::image('assets/img/icons/c_add_item.png')); ?></div>
<?php if (count($news->find_all()) > 0): ?>
    <table class="cab_c_table">
        <tr class="title">
            <td>Компания</td>
            <td>Заголовок</td>
            <td>Дата</td>
            <td></td>
        </tr>
        <?php foreach ($news->find_all() as $n): ?>
            <tr>
                <td><?= $n->service->name; ?></td>
                <td><?= $n->title; ?></td>
                <td><?= MyDate::show($n->date_create); ?></td>
                <td><?= HTML::anchor('cabinet/news/edit/'.$n->id, HTML::image('assets/img/icons/c_edit.png')); ?> <?= HTML::anchor('cabinet/news/delete/'.$n->id, HTML::image('assets/img/icons/c_delete.png')); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="title">
            <td>Компания</td>
            <td>Заголовок</td>
            <td>Дата</td>
            <td></td>
        </tr>
    </table>
<?php else: ?>
    <p>Нет новостей, <?= HTML::anchor('cabinet/news/add', 'добавить'); ?></p>
<?php endif; ?>

