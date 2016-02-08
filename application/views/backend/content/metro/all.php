<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/content')
         ->set('current_url', 'admin/content/metro');
echo View::factory('backend/navigation/cities')
         ->set('url', 'admin/content/metro/index/')
         ->set('city_id', $city_id)
         ->set('cities', $cities);
?>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 200px;">Станция метро</th>
            <th>Текст</th>
            <th style="width: 110px;">Дата ред.</th>
            <th style="width: 45px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($content->find_all() as $c): ?>
        <tr>
            <td style="width: 300px;">Станция метро <?= $c->metro->name.' г.'.$c->metro->city->genitive_name;; ?></td>
            <td><?= (mb_strlen($c->text) > 0) ? Text::limit_words(strip_tags($c->text), 30) : 'Текст отсутствует'; ?></td>
            <td><?= ($c->date_edited != '0000-00-00 00:00:00') ? MyDate::show($c->date_edited, TRUE) : 'ни разу' ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/content/metro'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
