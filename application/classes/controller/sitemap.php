<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Sitemap extends Controller
{
    function action_index()
    {
        $this->auto_render = FALSE;

        $sitemap = new Sitemap();
        $base_url = 'http://www.as-avtoservice.ru/';
        // Basic urls
        $base_urls = Kohana::$config->load('settings.sitemap_urls');
        foreach ($base_urls as $u)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.$u);
            $sitemap->add($url);
        }
        // Standart contents
        $content = ORM::factory('content_site');
        foreach ($content->where('active', '!=', 0)->find_all() as $c)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.$c->url);
            //$unix_time = strtotime($c->date);
            //$url->set_last_mod($unix_time);
            $sitemap->add($url);
        }
        // Shops page
        $shop = ORM::factory('service')->where('type', '=', 2);
        foreach ($shop->find_all() as $s)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'shops/'.$s->id);
            //$unix_time = (int) strtotime($s->date_create);
            //$url->set_last_mod($unix_time);
            $sitemap->add($url);
        }

        // Services page
        $service = ORM::factory('service')->where('type', '=', 1);
        foreach ($service->find_all() as $s)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'services/'.$s->id);
            $sitemap->add($url);
        }
        unset ($s);
        // Service news
        $service_all_news_urls = array();
        $news_service = ORM::factory('newsservice')->where('active', '=', 1);
        foreach ($news_service->find_all() as $news)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'services/'.$news->service->id.'/news/'.$news->id);
            $sitemap->add($url);
            if (!array_key_exists($news->service->id, $service_all_news_urls))
                $service_all_news_urls[$news->service->id] = 'services/'.$news->service->id.'/news';
        }
        unset ($news);
        foreach ($service_all_news_urls as $all_news_url)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.$all_news_url);
            $sitemap->add($url);
        }

        // Site news
        $news_portal = ORM::factory('newsportal')->where('active', '!=', 0);
        foreach ($news_portal->find_all() as $news)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'news/association/'.$news->id);
            $sitemap->add($url);
        }
        unset ($news);
        // World news
        $news_world = ORM::factory('newsworld')->where('active', '!=', 0)->find_all();
        if (count($news_world) > 0)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'news/world');
            $sitemap->add($url);
            foreach ($news_world as $news)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'news/world/'.$news->id);
                $sitemap->add($url);
            }
        }
        // Articles
        $article = ORM::factory('content_article')->where('active', '!=', 0)->find_all();
        if (count($article) > 0)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'articles');
            $sitemap->add($url);
            foreach ($article as $a)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'articles/'.$a->id);
                $sitemap->add($url);
            }
        }
        // Reviews
        $service_all_reviews_urls = array();
        $review = ORM::factory('review')->where('active', '!=', 0)->find_all();
        if (count($review) > 0)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'reviews');
            $sitemap->add($url);
            foreach ($review as $r)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'services/'.$r->service->id.'/reviews/'.$r->id);
                $sitemap->add($url);
                if (!array_key_exists($r->service->id, $service_all_reviews_urls))
                    $service_all_reviews_urls[$r->service->id] = 'services/'.$r->service->id.'/reviews';
            }
        }
        foreach ($service_all_reviews_urls as $all_reviews_url)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.$all_reviews_url);
            $sitemap->add($url);
        }
        // Services stocks (акции)
        $service_all_stocks_urls = array();
        $stock = ORM::factory('stock')->where('active', '!=', 0)->find_all();
        if (count($stock) > 0)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'stocks');
            $sitemap->add($url);
            foreach ($stock as $s)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'services/'.$s->service->id.'/stocks/'.$s->id);
                $sitemap->add($url);
                if (!array_key_exists($s->service->id, $service_all_stocks_urls))
                    $service_all_stocks_urls[$s->service->id] = 'services/'.$s->service->id.'/stocks';
            }
        }
        foreach ($service_all_stocks_urls as $all_stocks_url)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.$all_stocks_url);
            $sitemap->add($url);
        }
        // Services Vacancies (вакансии)
        $service_all_vacancies_urls = array();
        foreach (ORM::factory('vacancy')->get_vacancies() as $v)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'services/'.$v->service->id.'/vacancies/'.$v->id);
            $sitemap->add($url);
            if (!array_key_exists($v->service->id, $service_all_vacancies_urls))
                $service_all_vacancies_urls[$v->service->id] = 'services/'.$v->service->id.'/vacancies';
        }
        foreach ($service_all_vacancies_urls as $all_vacancies_url)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.$all_vacancies_url);
            $sitemap->add($url);
        }
        // Q&A (запросы пользователей)
        $question = ORM::factory('question')->where('active', '!=', 0)->find_all();
        if (count($question) > 0)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'messages');
            $sitemap->add($url);
            foreach ($question as $q)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'messages/'.$q->id);
                $sitemap->add($url);
            }
        }

        $geo_params = Geography::get_geography_params();

        // Tags pages
        // Auto tags and search pages
        foreach ($geo_params['cities']['has_cars'] as $city)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'filter/auto/city_'.$city['city_id']);
            $sitemap->add($url);
            foreach ($city['cars'] as $car_id)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'services/search/car_'.$car_id.'/city_'.$city['city_id']);
                $sitemap->add($url);
            }
        }
        unset ($city);
        // Works tags and search pages
        foreach ($geo_params['cities']['has_works'] as $city)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'filter/work/city_'.$city['city_id']);
            $sitemap->add($url);
            foreach ($city['works'] as $work_id)
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'services/search/work_'.$work_id.'/city_'.$city['city_id']);
                $sitemap->add($url);
            }
        }
        unset ($city);
        // Districts tags and search pages
        foreach ($geo_params['cities']['has_district'] as $city)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'filter/district/city_'.$city['city_id']);
            $sitemap->add($url);
        }
        unset ($city);
        foreach ($geo_params['districts'] as $id => $value)
        {
            if (!empty($value['cars']) OR !empty($value['works']))
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'services/search/district_'.$id);
                $sitemap->add($url);
            }
        }
        // Metro stations tags and search pages
        foreach ($geo_params['cities']['has_metro'] as $city)
        {
            $url = new Sitemap_URL();
            $url->set_loc($base_url.'filter/metro/city_'.$city['city_id']);
            $sitemap->add($url);
        }
        foreach ($geo_params['metro_stations'] as $id => $value)
        {
            if (!empty($value['cars']) OR !empty($value['works']))
            {
                $url = new Sitemap_URL();
                $url->set_loc($base_url.'services/search/metro_'.$id);
                $sitemap->add($url);
            }
        }


        $this->response->headers('Content-type', 'text/xml');
        $this->response->body($sitemap);
    }
}