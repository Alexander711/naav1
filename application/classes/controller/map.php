<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Map extends Controller_Frontend
{
    function action_index()
    {
        $services_urls = array();
        $pages = array();
        $service_news_urls = array();
        $shops_urls = array();
        $stocks_urls = array();
        $vacancies_urls = array();
        $reviews_urls = array();
        $questions_urls = array();
        $portal_news_urls = array();
        $world_news_urls = array();
        $articles_urls = array();

        $content_portal = ORM::factory('content_site')->where('active', '=', 1)->order_by('id', 'DESC');
        foreach ($content_portal->find_all() as $content)
        {
            $pages[$content->url] = $content->title;
        }
        $pages['search'] = 'Поиск';
        $service = ORM::factory('service')->where('type', '=', 1);
        foreach ($service->find_all() as $s)
        {
            $services_urls['services/'.$s->id] = __('company_type_1').' '.$s->orgtype->name.' &laquo;'.$s->name.'&raquo;';
        }
        $shop = ORM::factory('service')->where('type', '=', 2);
        foreach ($shop->find_all() as $s)
        {
            $shops_urls['shops/'.$s->id] = __('company_type_2').' '.$s->orgtype->name.' &laquo;'.$s->name.'&raquo;';
        }

        $news_service = ORM::factory('newsservice')->where('active', '=', 1);
        foreach ($news_service->find_all() as $n)
        {
            $service_news_urls['services/'.$n->service->id.'/news/'.$n->id] = 'Новость "'.$n->title.'" от '.$n->service->name;
        }
        $stock = ORM::factory('stock')->where('active', '=', 1);
        foreach ($stock->find_all() as $s)
        {
            $stocks_urls['services/'.$s->service->id.'/stocks/'.$s->id] = 'Акция автосервиса '.$s->service->name;
        }
        $vacancy = ORM::factory('vacancy')->where('active', '=', 1);
        foreach ($vacancy->find_all() as $v)
        {
            $vacancies_urls['services/'.$v->service->id.'/vacancies/'.$v->id] = $v->title.' в '.$v->service->name;
        }
        $review = ORM::factory('review')->where('active', '=', 1);
        foreach ($review->find_all() as $r)
        {
            $reviews_urls['services/'.$r->service->id.'/reviews/'.$r->id] = 'Отзыв на компанию '.$r->service->name;
        }
        $question = ORM::factory('question')->where('active', '=', 1)->order_by('date', 'DESC');
        foreach ($question->find_all() as $q)
        {
            $questions_urls['messages/'.$q->id] = 'Запрос на '.$q->carbrand->name.' '.$q->model->name.' '.$q->volume.' '.$q->gearbox->name.' '.$q->year;
        }
        $news_portal = ORM::factory('newsportal')->where('active', '=', 1)->order_by('date', 'DESC');
        foreach ($news_portal->find_all() as $n)
        {
            $portal_news_urls['news/association/'.$n->id] = $n->title;
        }
        $news_world = ORM::factory('newsworld')->where('active', '=', 1)->order_by('date', 'DESC');
        foreach ($news_world->find_all() as $n)
        {
            $world_news_urls['news/world/'.$n->id] = $n->title;
        }
        foreach ($this->_geo_params['cities']['has_cars'] as $city_id => $value)
        {
            $cars_tags_urls['filter/auto/city_'.$city_id] = 'Подбор автосервисов по марке автомобиля в '.$this->_geo_params['cities']['total'][$city_id]->dativus_name;
        }
        foreach ($this->_geo_params['cities']['has_works'] as $city_id => $value)
        {
            $works_tags_urls['filter/work/city_'.$city_id] = 'Подбор автосервисов по предоставляемым услугам в '.$this->_geo_params['cities']['total'][$city_id]->dativus_name;
        }
        foreach ($this->_geo_params['cities']['has_district'] as $city_id => $value)
        {
            $districts_tags_urls['filter/district/city_'.$city_id] = 'Подбор автосервисов по округу в '.$this->_geo_params['cities']['total'][$city_id]->dativus_name;
        }
        foreach ($this->_geo_params['cities']['has_metro'] as $city_id => $value)
        {
            $metro_tags_urls['filter/metro/city_'.$city_id] = 'Подбор автосервисов по станции метро в '.$this->_geo_params['cities']['total'][$city_id]->dativus_name;
        }
        foreach (ORM::factory('content_article')->where('active', '=', 1)->find_all() as $a)
        {
            $articles_urls['articles/'.$a->id] = $a->title;
        }
        $this->view = View::factory('frontend/sitemap')
                          ->set('pages', $pages)
                          ->set('cars_tags_urls', $cars_tags_urls)
                          ->set('works_tags_urls', $works_tags_urls)
                          ->set('districts_tags_urls', $districts_tags_urls)
                          ->set('metro_tags_urls', $metro_tags_urls)
                          ->set('services_urls', $services_urls)
                          ->set('shops_urls', $shops_urls)
                          ->set('service_news_urls', $service_news_urls)
                          ->set('stocks_urls', $stocks_urls)
                          ->set('vacancies_urls', $vacancies_urls)
                          ->set('reviews_urls', $reviews_urls)
                          ->set('questions_urls', $questions_urls)
                          ->set('portal_news_urls', $portal_news_urls)
                          ->set('world_news_urls', $world_news_urls)
                          ->set('articles_urls', $articles_urls);
        $this->template->title = 'Карта сайта';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;

    }
}