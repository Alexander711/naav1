<?php
defined('SYSPATH') or die('No direct script access.');
echo View::factory('backend/navigation/content')
         ->set('current_url', 'admin/content/works')
    .View::factory('backend/search_block')
         ->set('url', 'admin/content/works')
         ->set('values', $values);
?>
<p>
    <?= HTML::anchor('admin/content/works/default_text', 'Редактирования текста по умолчанию', array('class' => 'btn btn-primary')); ?>
</p>
<div class="clearfix">
    <ul class="nav nav-tabs">
        <?php foreach ($cities->find_all() as $city): ?>
            <?php $attr = ($city_id == $city->id) ? array('class' => 'active') : NULL; ?>
            <li <?= HTML::attributes($attr); ?>><?= HTML::anchor('admin/content/works/index/'.$city->id, $city->name); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 200px;">Услуга</th>
            <th>Текст</th>
            <th style="width: 110px;">Дата ред.</th>
            <th style="width: 45px;"></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($content->find_all() as $c): ?>
        <tr>
            <td style="cursor: help;" title="<?= $c->work->name; ?>"><?= Text::limit_chars($c->work->name, 40); ?></td>
            <td><?= (mb_strlen($c->text) > 0) ? Text::limit_words(strip_tags($c->text), 30) : 'Текст отсутствует'; ?></td>
            <td><?= ($c->date_edited != '0000-00-00 00:00:00') ? MyDate::show($c->date_edited, TRUE) : 'ни разу' ?></td>
            <td><?= HTML::edit_button($c->id, 'admin/content/works'); ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>