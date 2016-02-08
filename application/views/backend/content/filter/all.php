<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/content')
         ->set('current_url', 'admin/content/filter');
?>
<div class="clearfix" style="margin-bottom: 10px;">
    <ul class="nav nav-pills">
        <?php foreach ($types as $type => $value): ?>
            <li class="<?= $value['css_class']; ?>"><?= HTML::anchor('admin/content/filter/index/'.$type, $value['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr class="title">
            <th style="width: 150px;">Город</th>
            <th>Текст</th>
            <th style="width: 110px;">Дата ред.</th>
            <th style="width: 45px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($content->order_by('city_id', 'ASC')->find_all() as $c): ?>
        <tr>
            <td><?= ($c->city_id != 0) ? $c->city->name : 'Все'; ?></td>
            <td><?= (mb_strlen($c->text) > 0) ? Text::limit_words(strip_tags($c->text), 30) : 'Текст отсутствует'; ?></td>
            <td><?= ($c->date_edited != '0000-00-00 00:00:00') ? MyDate::show($c->date_edited) : 'ни разу' ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/content/filter'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>