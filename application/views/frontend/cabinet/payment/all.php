<?php
defined('SYSPATH') or die('No direct script access.');
$invoices->reset(FALSE);

$statuses = array("N"=>"Новый","P"=>"Оплачен","C"=>"Отменен","E"=>"Просрочен");

?>

<div class="add_item"><?= HTML::anchor('cabinet/payment/add', 'Добавить платеж '.HTML::image('assets/img/icons/c_add_item.png')); ?></div>
<?php if (count($invoices->find_all()) > 0): ?>
    <table class="table-hover table">
        <tr class="title">
            <td>ID</td>
            <td>Создан</td>
            <td>Изменен</td>
            <td>Сумма</td>
            <td>Дней</td>
            <td>Статус</td>
        </tr>
        <?php foreach ($invoices->order_by('id', 'DESC')->find_all() as $n): ?>
            <tr>
                <td><?= HTML::anchor('cabinet/payment/show/'.$n->id, 'UPI - '.$n->id); ?></td>
                <td><?= Date::formatted_time($n->create_date,'d.m.Y H:i'); ?></td>
                <td><?= Date::formatted_time($n->modify_date,'d.m.Y H:i'); ?></td>
                <td><?= $n->amount; ?></td>
                <td><?= $n->days_amount; ?></td>
                <td><?= $statuses[$n->status]; ?></td>
            </tr>
        <?php endforeach; ?>

    </table>
<?php else: ?>
    <p>Нет платежей, <?= HTML::anchor('cabinet/payment/add', 'добавить'); ?></p>
<?php endif; ?>