<?php
defined('SYSPATH') or die('No direct script access.');
return array(
    'content_not_found' => 'Контент не найден',
    'content_is_active' => 'Контент уже активный',
    'content_is_unactive' => 'Контент уже неактивный',
    'user_not_found' => 'Пользователь не найден',
    'user_is_active' => 'Пользователь уже активен',
    'user_is_unactive' => 'Пользоывткль уже неактивный',
    'user_havent_services' => 'Пользователь не имеет компаний',
    'service_not_found' => 'Автосервис не найден',
    'service_is_active' => 'Автосервис уже активен',
    'service_is_unactive' => 'Автосервис уже неактивный',
    'news_not_found' => 'Новость не найдена',
    'news_is_active' => 'Новость уже активна',
    'news_is_unactive' => 'Новость уже неактивна',
    'stock_not_found' => 'Акция не найдена',
    'stock_is_active' => 'Акция уже активна',
    'stock_is_unactive' => 'Акция уже неактивна',
    'vacancy_not_found' => 'Акция не найдена',
    'vacancy_is_active' => 'Акция уже активна',
    'vacancy_is_unactive' => 'Акция уже неактивна',
    'city'  => array(
        'not_found' => 'Город не найден',
        'add_success_text' => '<p>Город ":name" успешно добавлен</p>',
        'edit_success' => '<p>Город ":name" успешно отредактирован</p>',
        'success_work_car_content_urls' => '
              <p><a href="'.URL::base().'/admin/content/filter/edit/:content_id_car">Редактировать</a> страницу фильтра по маркам авто</p>
              <p><a href="'.URL::base().'/admin/content/filter/edit/:content_id_work">Редактировать</a> страницу фильтра по маркам услуге</p>
        ',
        'success_district_urls' => '<p><a href="'.URL::base().'/admin/content/filter/edit/:content_id_district">Редактировать</a> страницу фильтра по округу</p>',
        'success_metro_urls' => '<p><a href="'.URL::base().'/admin/content/filter/edit/:content_id_metro">Редактировать</a> страницу фильтра по станции метро</p>'
    ),
    'metro' => array(
        'not_found' => 'Станция метро не найдена',
        'add_success' => 'Станция метро ":name" успешно добавлена',
        'edit_success' => 'Станция метро ":name" успешно отредактирована',
        'editing' => '<p><a href="'.URL::base().'/admin/content/metro/edit/:content_id">Редактировать</a> страницу поиска по данной станции</p>',
        'delete_question' => 'Вы действительно хотите удалить станцию метро ":name". ',
        'delete_question_services' => 'Удаление коснется след. кол-ва компаний: :count.',
        'delete_success' => 'Станция метро ":name" успешно удалена. ',
        'delete_success_services' => 'Это коснулось след. кол-ва сервисов: :count.'
    ),
    'district' => array(
        'not_found' => 'Округ не найден',
        'add_success' => 'Округ ":name" успешно добавлен',
        'edit_success' => 'Округ ":name" успешно отредактирован',
        'editing' => '<p><a href="'.URL::base().'/admin/content/district/edit/:content_id">Редактировать</a> страницу поиска по данному округу</p>',
        'delete_question' => 'Вы действительно хотите удалить округ ":name". ',
        'delete_success' => 'Округ ":name" успешно удалена. ',
        
    ),
    'work_category' => array(
        'not_found',
        'add_success' => 'Категория ":name" успешно добавлена',
        'edit_success' => 'Категория ":name" успешно отредактирована.',
        'delete_question' => 'Вы действительно хотите удалить категорию услуг ":name"? ',
        'delete_question_works' => 'Удаление данной категории повлечет удаление след. кол-ва услуг: :count',
        'delete_success' => 'Категория услуг ":name" успешно удалена. ',
        'delete_success_works' => 'След. кол-во услуг удалено вместе с категорией: :count'
    ),
    'work' => array(
        'not_found' => 'Услуга не найдена',
        'add_success' => 'Услуга ":name" успешно добавлена',
        'edit_success' => 'Услуга ":name" успешно отредактирована',
        'delete_question' => 'Вы действительно хотите удалить услугу ":name"? ',
        'delete_question_services' => 'Удаление услуги коснется след. кол-ва компаний: :count',
        'delete_success' => 'Услуга ":name" успешно удалена. ',
        'delete_success_services' => 'Это коснулось след. кол-ва компний: :count. '
    ),
    'content_article' => array(
        'not_found' => 'Статья не найдена',
        'add_success' => 'Статья ":name" успешно добавлена',
        'edit_success' => 'Статья ":name" успешно отредактирована',
        'delete_question' => 'Вы действительно хотите удалить статью ":name"?',
        'delete_success' => 'Статья ":name" успешно удалена',
        'is_active' => 'Статья итак активна',
        'is_unactive' => 'Статья итак неактивна',
        'activate_success' => 'Статья активирована',
        'deactivate_success' => 'Статьия деактивирована'
    ),
    'work_category_not_found' => 'Категория услуг не найдена',
    'company_has_been_deleted' => 'Компания :company_name успешно удалена',
    'payment' => array(
          'settings_not_found' => 'Настройка не найдена',
          'add_success' => 'Услуга ":name" успешно добавлена',
          'edit_success' => 'Услуга ":name" успешно отредактирована',
          'delete_question' => 'Вы действительно хотите удалить услугу ":name"? ',
          'delete_question_services' => 'Удаление услуги коснется след. кол-ва компаний: :count',
          'delete_success' => 'Услуга ":name" успешно удалена. ',
          'delete_success_services' => 'Это коснулось след. кол-ва компний: :count. '
      ),
);