<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_404error extends Controller_Frontend
{
    function action_index()
    {
        $this->template->bc['#'] = 'Ошибка';
        $this->view = View::factory('404view')
                          ->set('message', $this->request->post('message'));
        $this->template->title = 'Ошибка';
        $this->template->content = $this->view;
    }
}