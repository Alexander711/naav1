<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_News_Main extends Controller_Backend
{
    function action_index()
    {
        $this->template->content = 'All news';
    }
}