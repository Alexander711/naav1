<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/content')
         ->set('current_url', 'admin/content/cars')
    .View::factory('backend/search_block')
         ->set('url', 'admin/content/cars')
         ->set('values', $values);
?>

<p><?= HTML::anchor('admin/content/cars/default_text', 'Редактирования текста по умолчанию', array('class' => 'btn btn-primary')); ?></p>
<div class="clearfix">
    <ul class="nav nav-tabs">
        <?php foreach ($cities as $city): ?>
            <?php $attr = ($city_id == $city->id) ? array('class' => 'active') : NULL; ?>
            <li <?= HTML::attributes($attr); ?>><?= HTML::anchor('admin/content/cars/index/'.$city->id, $city->name); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Марка авто</th>
            <th>Текст</th>
            <th style="width: 110px;">Дата ред.</th>
            <th style="width: 45px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($content->find_all() as $c): ?>
        <tr>
            <td style="font-weight: bold;"><?= ($c->car->name != '') ? $c->car->name : $c->car->name_ru; ?></td>
            <td><?= (mb_strlen($c->text) > 0) ? Text::limit_words(strip_tags($c->text), 30) : 'Текст отсутствует'; ?></td>
            <td><?= ($c->date_edited != '0000-00-00 00:00:00') ? MyDate::show($c->date_edited) : 'ни разу' ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/content/cars'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>