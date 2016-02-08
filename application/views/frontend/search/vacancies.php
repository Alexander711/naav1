<?php
defined('SYSPATH') or die('No direct script access.');
?>


<div class="search-result vacancies">
    <h1 class="title">Найдено Вакансий <?= count($vacancies); ?></h1>
    <?php foreach ($vacancies as $v): ?>
        <div class="content-group">
            <div class="header with-date">
                <div class="title"><?= HTML::anchor(Model_Service::$type_urls[$v->service->type].'/'.$v->service->id.'/vacancies/'.$v->id, $v->title); ?></div>
                <div class="date"><?= Date::full_date($v->date); ?></div>
            </div>

            <div class="body">
                <div class="text">
                    <?= Text::limit_words(strip_tags($v->text), 100); ?>
                    <?= HTML::anchor(Model_Service::$type_urls[$v->service->type].'/'.$v->service->id.'/vacancies/'.$v->id, 'Подробнее'); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>



