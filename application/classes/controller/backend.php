<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Backend extends Controller_MainTemplate
{
    protected $_base_url;
    public $template = 'templates/backend';
    function before()
    {
        parent::before();
        $this->response->headers('Pragma', 'no-cache');
        if (!$this->auth->logged_in('admin'))
        {
            $this->request->redirect('admin/login');
        }
        $this->template->bc['admin'] = 'Главная';
        // Стили
        $this->add_css('assets/bootstrap/css/bootstrap.css');
        $this->add_css('assets/bootstrap/css/datepicker.css');
        $this->add_css('assets/js/css/flick/jquery-ui-1.8.16.custom.css');
        $this->add_css('assets/js/multiselect/jquery.multiselect.css');
        $this->add_css('assets/css/admin.css');
        // Скрипты
        $this->add_js('assets/js/jquery-1.7.1.js');
        $this->add_js('assets/js/jquery-ui-1.8.17.custom.min.js');
        $this->add_js('assets/bootstrap/js/prettify.js');

        $this->add_js('assets/js/tiny_mce/jquery.tinymce.js');

        $this->add_js('assets/js/ckeditor/ckeditor.js');
        $this->add_js('assets/js/jquery.showpassword.js');
        $this->add_js('assets/js/multiselect/jquery.multiselect.js');
        $this->add_js('assets/js/multiselect/jquery.multiselect.filter.js');
        $this->add_js('assets/js/jquery.qtip-1.0.0-rc3.js');
        $this->add_js('assets/js/jquery.tablesorter.min.js');

        $this->add_js('assets/bootstrap/js/bootstrap.js');
        $this->add_js('assets/bootstrap/js/bootstrap-datepicker.js');
        $this->add_js('assets/js/jquery.columnizer.min.js');
        $this->add_js('assets/js/admin_main.js');
    }
    protected function get_orm_model($model_name, $redirect_url = NULL)
    {
        $model = ORM::factory($model_name, $this->request->param('id', NULL));
        if (!$model->loaded())
        {
            Message::set(Message::ERROR, __(Kohana::message('admin', $model_name.'.not_found')));
            if ($redirect_url)
                $this->request->redirect($redirect_url);
            else
                $this->request->redirect('admin');
        }
        return $model;
    }
    function after()
    {
        parent::after();
    }
}