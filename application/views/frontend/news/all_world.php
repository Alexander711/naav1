<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<h1 style="margin-bottom: 20px;">Новости автомира</h1>
<div class="content_all">
    <ul>
        <?php foreach ($news->order_by('date', 'DESC')->find_all() as $n): ?>
            <li>
                <div class="title"><?= HTML::anchor($url.$n->id, $n->title); ?></div>
                <div class="date"><?= MyDate::show($n->date); ?></div>
                <div class="text"><?= Text::limit_words($n->text, 70); ?></div>
                <div class="more"><?= HTML::anchor('news/world/'.$n->id, 'Подробнее')?></div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
