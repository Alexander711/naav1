<?php
defined('SYSPATH') or die('No direct script access.');
?>


<div class="search-result stocks">
    <h1 class="title">Найдено Акций <?= count($stocks); ?></h1>
    <?php foreach ($stocks as $s): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor(Model_Service::$type_urls[$s->service->type].'/stocks/'.$s->id, 'Акция '.mb_strtolower(__('company_type_'.$s->service->type.'_genitive')).' '.$s->service->name); ?></div>
                <div class="date"><?= Date::full_date($s->date); ?></div>
            </div>
            <div class="body">
                <div class="text">
                    <?= Text::limit_words(strip_tags($s->text), 100); ?>
                    <?= HTML::anchor(Model_Service::$type_urls[$s->service->type].'/stocks/'.$s->id, 'Подробнее'); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>


