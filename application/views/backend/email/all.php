<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?php echo $pagination; ?>
<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>От</th>
        <th>Кому</th>
        <th>Заголовок</th>
        <th>Дата отправки в очередь</th>
        <th>Дата отправки Email</th>
        <th>Статус</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($email->order_by('date_create', 'DESC')->find_all() as $e): ?>
        <?php
        switch ($e->status)
        {
            case 'queue':
                $td_attr['style'] = '';
                $td_text = 'В ожидании';
                break;
            case 'send':
                $td_attr['style'] = '';
                $td_text = 'Отправлено';
                break;
            case 'error':
                $td_attr['style'] = '';
                $td_text = 'Ошибка отправки';
                break;
            default:
                $td_attr['style'] = '';
                $td_text = 'Неизвестно';
        }
        ?>
        <tr>
            <td><?= $e->mail_from; ?></td>
            <td><?= $e->mail_to; ?></td>
            <td><?= $e->title; ?></td>
            <td><?= MyDate::show($e->date_create, TRUE); ?></td>
            <td><?= ($e->date_send == '0000-00-00 00:00:00') ? 'Неизвестно' : MyDate::show($e->date_send, TRUE); ?></td>
            <td><?= $td_text; ?></td>
            <td>
                <?= HTML::anchor('admin/email', 1); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php echo $pagination; ?>