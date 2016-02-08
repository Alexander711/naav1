<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'site_name' => '',
    'quarantine' => FALSE,
    // Ключ для API Яндекс карт
    'YMaps_key' => 'AHsACE4BAAAAWheEBgIAl_ljYAxgj3KoQ3P4LkcpLKp3pskAAAAAAAAAAAAvJvgAZ0PRsCqOjDxZlQf3S6p8IQ==',
    'company_types' => array(
        '1' => 'Автосервис',
        '2' => 'Магазин автозапчастей'
    ),
    'company_type_url' => array(
        1 => 'services',
        2 => 'shops'
    ),
    // Список доступных форм компаний
    'org_types' => array(
        'OOO' => 'ООО',
        'OAO' => 'ОАО',
        'ZAO' => 'ЗАО',
        'IP'  => 'ИП'
    ),
    'sitemap_urls' => array(
        'news',
        'news/association',
        'shops',
        'vacancies',
        'messages',
        'reviews'
    ),
    // Список доступных видов скидок
    'discounts' => array(
        '0' => 'нет',
        '5' => '5%',
        '7' => '7%',
        '10' => '10%',
    ),
    'service_items' => array(
        'works',
        'cars'
    ),
    'services_items' =>array(
        'works' => array(
            'table_name' => 'services_works',
            'key'        => 'work_id'
        ),
        'cars' => array(
            'table_name' => 'services_cars',
            'key'        => 'car_id'
        )
    ),
    'service_keys' => array(
        'works' => 'work_id',
        'cars' => 'car_id'
    ),
    // Постраничная навигация
    'pagination' => array(
        'items_on_page' => 99999
    ),
    // Максимальное кол-во элементов на главной странице блока "Быстрый поиск по ..."
    'max_fast_filter_cities' => 3,
    'max_fast_filter_models' => 21,
    'max_fast_filter_works' => 14,
    'max_fast_filter_metro' => 14,
    'max_fast_filter_districts' => 14,
    // ID городов выводимых в первую очередь
    'fast_filter_cities' => array(
        1,
        2,
        5
    ),
    // ID марок автомобилей выводимых в первую очередь
    'fast_filter_models' => array(
        48,
        13,
        1,
        6,
        64,
        65,
        47,
        22,
        27,
        53,
        15,
        17,
        57,
        38,
        42,
        43,
        62,
        61,
        66,
        36,
        49
    ),
    // ID услуг выводимых в первую очередь
    'fast_filter_works' => array(
        22,
        23,
        58,
        59,
        29,
        63,
        64
    ),
    /**
     * Последний элемент не зря пустой,
     * для удаление пустых элементов поискового запроса,
     * такими они стали в результате обработки if strlen < 3 null;
     **/
    'search_stop_words' => array(
       'что', 'как', 'все', 'она', 'так', 'его', 'только', 'мне', 'было', 'вот',
       'меня', 'еще', 'нет', 'ему', 'теперь', 'когда', 'даже', 'вдруг', 'если',
       'уже', 'или', 'быть', 'был', 'него', 'вас', 'нибудь', 'опять', 'вам', 'ведь',
       'там', 'потом', 'себя', 'может', 'они', 'тут', 'где', 'есть', 'надо', 'ней',
       'для', 'тебя', 'чем', 'была', 'сам', 'чтоб', 'без', 'будто', 'чего', 'раз',
       'тоже', 'себе', 'под', 'будет', 'тогда', 'кто', 'этот', 'того', 'потому',
       'этого', 'какой', 'ним', 'этом', 'один', 'почти', 'мой', 'тем', 'чтобы',
       'нее', 'были', 'куда', 'зачем', 'всех', 'можно', 'при', 'два', 'другой',
       'хоть', 'после', 'над', 'больше', 'тот', 'через', 'эти', 'нас', 'про', 'них',
       'какая', 'много', 'разве', 'три', 'эту', 'моя', 'свою', 'этой', 'перед',
       'чуть', 'том', 'такой', 'более', 'всю','ремонт', ''
    ),
    'company_page' => array(
        'cars_show_count' => 15,
        'works_show_count' => 15
    )

);
