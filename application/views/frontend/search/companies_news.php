<?php
defined('SYSPATH') or die('No direct script access.'); ?>


<div class="search-result companies-news">
    <h1 class="title">Найдено Новостей компаний <?= count($news); ?></h1>
    <?php foreach ($news as $n): ?>
        <?php
        if ($n->service->type == Model_Service::AUTOSERVICE)
            $url = 'services/';
        elseif ($n->service->type == Model_Service::SHOP)
            $url = 'shops/';
        ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor($url.$n->service->id, $n->service->name, array('class' => 'red')).' | '.HTML::anchor($url.$n->service->id.'/news/'.$n->id, $n->title); ?></div>
                <div class="date"><?= Date::full_date($n->date_create); ?></div>
            </div>

            <div class="body">
                <div class="address"><?= $n->service->get_address(); ?></div>
                <noindex>
                    <div class="text">
                        <?= Text::limit_words(strip_tags($n->text), 300); ?>
                        <?= HTML::anchor($url.$n->service->id.'/news/'.$n->id, 'Подробнее'); ?>
                    </div>
                </noindex>
            </div>
        </div>
    <?php endforeach; ?>
</div>
