<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/service_items')
         ->set('current_url', 'admin/service/stock');
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr class="title">
            <th>Компания</th>
            <th>Дата</th>
            <th>Краткое содержание</th>
            <th>Статус</th>
            <th style="width: 72px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($stock->order_by('date', 'DESC')->find_all() as $s): ?>
        <tr>
            <td><?= $s->service->name; ?></td>
            <td><?= MyDate::show($s->date, TRUE); ?></td>
            <td><?= Text::limit_words(strip_tags($s->text), 7); ?></td>
            <td><?= HTML::activate_checker($s->id, $s->active, 'admin/service/stock'); ?></td>
            <td><?= HTML::edit_button($s->id, 'admin/service/stock').HTML::delete_button($s->id, 'admin/service/stock'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>