<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Gallery extends Controller_Cabinet
{
    public function before()
    {
        parent::before();
        $this->template->title = 'Галерея';
        $this->template->bc['#'] = $this->template->title;
    }
    public function action_index()
    {
        $this->view = View::factory('frontend/cabinet/gallery/all')
                          ->set('user', $this->user);

        $this->template->content = $this->view;
    }
}