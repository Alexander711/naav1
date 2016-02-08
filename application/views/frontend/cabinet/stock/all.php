<?php
defined('SYSPATH') or die('No direct script access.');
$stock->reset(FALSE);
?>
<div class="add_item"><?= HTML::anchor('cabinet/stock/add', 'Добавить акцию '.HTML::image('assets/img/icons/c_add_item.png')); ?></div>
<?php if (count($stock->find_all()) > 0): ?>
    <table class="cab_c_table">
        <tr class="title">
            <td>Компания</td>
            <td>Текст</td>
            <td>Дата</td>
            <td></td>
        </tr>
        <?php foreach ($stock->find_all() as $n): ?>
            <tr>
                <td><?= $n->service->name; ?></td>
                <td><?= $n->text; ?></td>
                <td><?= MyDate::show($n->date); ?></td>
                <td><?= HTML::anchor('cabinet/stock/edit/'.$n->id, HTML::image('assets/img/icons/c_edit.png')); ?> <?= HTML::anchor('cabinet/stock/delete/'.$n->id, HTML::image('assets/img/icons/c_delete.png')); ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="title">
            <td>Компания</td>
            <td>Текст</td>
            <td>Дата</td>
            <td></td>
        </tr>
    </table>
<?php else: ?>
    <p>Нет акций, <?= HTML::anchor('cabinet/stock/add', 'добавить'); ?></p>
<?php endif; ?>