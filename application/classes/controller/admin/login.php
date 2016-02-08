<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Login extends Controller
{
    function action_index()
    {
        if ($_POST)
        {
            if (Auth::instance()->login($_POST['username'], $_POST['password']))
            {
                if (Auth::instance()->logged_in('admin'))
                {
                    $this->request->redirect('admin');
                }
                else
                {
                    Auth::instance()->logout();
                }
            }
        }
        $view = View::factory('backend/login');
        $this->response->body($view);
    }
}