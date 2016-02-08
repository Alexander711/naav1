<?php
defined('SYSPATH') or die('No direct script access.');

?>
<!-- News -->
<div class="search-result news-<?= $css_class; ?>">
    <h1 class="title">Найдено новостей <?= $url == 'news/association' ? 'ассоциации' : 'автомира' ?> <?= count($news); ?></h1>
    <?php foreach ($news as $n): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor('news/world'.$n->id, $n->title); ?></div>
                <div class="date"><?= Date::full_date($n->date); ?></div>
            </div>

            <div class="body">
                <div class="text">
                    <?= Text::limit_words(strip_tags($n->text), 100); ?>
                    <?= HTML::anchor('news/world'.$n->id, 'Подробнее'); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>





