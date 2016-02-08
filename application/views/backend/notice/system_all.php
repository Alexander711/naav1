<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/notice')
         ->set('current_url', 'admin/notice/system');
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 350px;">Описание уведомления (не меняется)</th>
            <th>Текст</th>
            <th style="width: 36px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($notice->order_by('date', 'DESC')->find_all() as $c): ?>
        <tr>
            <td><?= $c->description; ?></td>
            <td><?= Text::limit_words(strip_tags($c->text), 120); ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/notice/edit_system', TRUE); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>