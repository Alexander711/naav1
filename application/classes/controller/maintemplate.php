<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_MainTemplate extends Controller_Template
{
    public $template = '';
    protected $view;

    protected $validation;
    protected $values = array();
    protected $errors = array();

    protected $session;

    protected $auth;
    protected $user;
    protected $cache;
    function before()
    {
        parent::before();
        $this->session = Session::instance();
        $this->auth = Auth::instance();
        $this->user = $this->auth->get_user();
        $this->cache = Cache::instance();
        if ($this->auto_render) 
        {
            // Initialize empty values
            $this->template->user = $this->user;
            $this->template->styles = array();
            $this->template->scripts = array();
            $this->template->title = '';
            $this->template->meta_keywords = '';
            $this->template->meta_description = '';
            $this->template->meta_copywrite = '';
            // Breadcrubms
            $this->template->bc = array();
            $this->template->content = '';
            $this->template->user = $this->user;
            $this->template->debug = array();
        }

        //View::set_global('user', $this->user);
        View::set_global('title', $this->template->title);
    }

    function after()
    {
        parent::after();
    }

    protected function add_debug($text)
    {
        $this->template->debug[] = $text;
    }
    /**
     * Добавление CSS файла
     * @param $file
     * @param string $media
     * @return void
     */
    protected function add_css($file, $media = 'screen')
    {
        $this->template->styles[] = array(
            'file' => $file,
            'type' => 'text/css',
            'media' => $media
        );
    }
    /**
     * Добавление JS скрипта
     * @param $file
     * @return void
     */
    protected function add_js($file)
    {
        $this->template->scripts[] = array(
            'file' => $file,
            'type' => 'text/javascript'
        );
    }
    /**
     * Скрипты и стили загрузчика изображений
     */
    protected function blueImpUploaderJs()
    {
        $this->add_css('assets/css/blueimpuploader.css');
        $this->add_js('assets/js/jquery.ui.widget.js');
        $this->add_js('http://blueimp.github.com/JavaScript-Templates/tmpl.min.js');
        $this->add_js('http://blueimp.github.com/JavaScript-Load-Image/load-image.min.js');
        $this->add_js('http://blueimp.github.com/JavaScript-Canvas-to-Blob/canvas-to-blob.min.js');
        $this->add_js('assets/js/uploader/jquery.iframe-transport.js');
        $this->add_js('assets/js/uploader/jquery.fileupload.js');
        $this->add_js('assets/js/uploader/jquery.fileupload-fp.js');
        $this->add_js('assets/js/uploader/jquery.fileupload-ui.js');
        $this->add_js('assets/js/uploader/locale.js');
        $this->add_js('assets/js/uploader.js');
    }
}