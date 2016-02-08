<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Content extends Controller_Frontend
{
    function action_index()
    {
        $url = $this->request->param('url');
        $content = ORM::factory('content_site')->where('url', '=', $url)->find();
        if (!$content->loaded())
        {
            $this->request->redirect('/');
        }
        $this->view = View::factory('frontend/content')
                          ->set('content', $content);
        $this->template->meta_keywords = $content->keywords;
        $this->template->meta_description = $content->description;
        $this->template->title = $this->site_name.$content->title;
        $this->template->bc['#'] = $content->title;

        $this->template->content = $this->view;
    }
}