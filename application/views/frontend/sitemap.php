<?php
defined('SYSPATH') or die('No direct script access.');?>
<ul>
    <li>
        Подбор автосервисов по марке автомобиля
        <ul>
            <?php foreach ($cars_tags_urls as $url => $name): ?>
                <li><?= HTML::anchor($url, $name); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        Подбор автосервисов по предоставляемым услугам
        <ul>
            <?php foreach ($works_tags_urls as $url => $name): ?>
                <li><?= HTML::anchor($url, $name); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        Подбор автосервисов по округу
        <ul>
            <?php foreach ($districts_tags_urls as $url => $name): ?>
                <li><?= HTML::anchor($url, $name);?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        Подбор автосервисов по станции метро
        <ul>
            <?php foreach ($metro_tags_urls as $url => $name): ?>
                <li><?= HTML::anchor($url, $name);?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        Автосервисы
        <ul>
            <?php foreach ($services_urls as $url => $name): ?>
                <li><?= HTML::anchor($url, $name); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('shops', 'Магазины автозапчастей'); ?>
        <ul>
            <?php foreach ($shops_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('news', 'Новости автосервисов'); ?>
        <ul>
            <?php foreach ($service_news_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('stocks', 'Акции автосервисов'); ?>
        <ul>
            <?php foreach ($stocks_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('vacancies', 'Вакансии автосервисов'); ?>
        <ul>
            <?php foreach ($vacancies_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('reviews', 'Отзывы'); ?>
        <ul>
            <?php foreach ($reviews_urls as $url => $name): ?>
                <li><?= HTML::anchor($url, $name); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('messages', 'Запросы автолюбителей'); ?>
        <ul>
            <?php foreach ($questions_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title) ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('news/world', 'Новости автомира'); ?>
        <ul>
            <?php foreach ($world_news_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title) ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('articles', 'Статьи'); ?>
        <ul>
            <?php foreach ($articles_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title); ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <li>
        <?= HTML::anchor('news/association', 'Новости ассоциации'); ?>
        <ul>
            <?php foreach ($portal_news_urls as $url => $title): ?>
                <li><?= HTML::anchor($url, $title) ?></li>
            <?php endforeach; ?>
        </ul>
    </li>
    <?php foreach ($pages as $url => $title): ?>
        <li><?= HTML::anchor($url, $title) ?></li>
    <?php endforeach; ?>
</ul>