<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Main extends Controller_Frontend
{
    
    function action_index()
    {
        Model_Service::generate_ratings();
        $servicenews = ORM::factory('newsservice');
        $portal_news = ORM::factory('newsportal');
        $news_world = ORM::factory('newsworld');
        $stock = ORM::factory('stock');
        $review = ORM::factory('review');
        $question = ORM::factory('question');
        $article = ORM::factory('content_article');




        $this->view = View::factory('frontend/main')
                          ->set('user', $this->user)
                          ->set('filter_types', Kohana::$config->load('fast_filter'))
                          ->set('current_filter_type', Cookie::get('fast_filter_type', 'cars'))
                          ->set('filter_cities', ORM::factory('city'))
                          ->set('questions', $question)
                          ->set('stocks', $stock)
                          ->set('portal_news', $portal_news)
                          ->set('service_news', $servicenews)
                          ->set('world_news', $news_world)
                          ->set('service_reviews', $review)
                          ->set('articles', $article);


        $this->add_js('http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU');
        $this->add_js('assets/js/ember/libs/handlebars-1.0.0.beta.6.js');
        $this->add_js('assets/js/ember/libs/ember.js');
        $this->add_js('assets/js/ember/app.js');

        $content = ORM::factory('content_site')->where('url', '=', 'home')->find();

        $this->template->title = 'Все автосервисы России и Москвы - Ассоциация автосервисов';
        $this->template->meta_description = $content->description;
        $this->template->meta_keywords = $content->keywords;
        $this->template->content = $this->view;
    }
}