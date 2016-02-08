<?php
defined('SYSPATH') or die('No direct script access.');
$vacancy->reset(FALSE);
?>
<div class="add_item"><?= HTML::anchor('cabinet/vacancy/add', 'Добавить вакансию '.HTML::image('assets/img/icons/c_add_item.png')); ?></div>
<?php if (count($vacancy->find_all()) > 0): ?>
    <table class="cab_c_table">
        <tr class="title">
            <td>Компания</td>
            <td>Заголовок</td>
            <td>Дата</td>
            <td></td>
        </tr>
        <?php foreach ($vacancy->find_all() as $n): ?>
            <tr>
                <td><?= $n->service->name; ?></td>
                <td><?= $n->title; ?></td>
                <td><?= MyDate::show($n->date); ?></td>
                <td><?= HTML::anchor('cabinet/vacancy/edit/'.$n->id, HTML::image('assets/img/icons/c_edit.png')); ?> <?= HTML::anchor('cabinet/vacancy/delete/'.$n->id, HTML::image('assets/img/icons/c_delete.png')); ?></td>
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
    <p>Нет вакансий, <?= HTML::anchor('cabinet/vacancy/add', 'добавить'); ?></p>
<?php endif; ?>