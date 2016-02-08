<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Main extends Controller_Backend
{
    function action_index()
    {
        $service = ORM::factory('service')->order_by('date_create', 'DESC')->limit(10);
        $feedback = ORM::factory('feedback')->order_by('date', 'DESC')->limit(5);
        $stock = ORM::factory('stock')->order_by('date', 'DESC')->limit(5);
        $news = ORM::factory('newsservice')->order_by('date_create', 'DESC')->limit(5);
        $this->view = View::factory('backend/main')
                          ->set('service', $service)
                          ->set('feedback', $feedback)
                          ->set('stock', $stock)
                          ->set('news', $news);
        $this->template->content = $this->view;
    }
}