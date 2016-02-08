<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Frontend extends Controller_MainTemplate
{
    public $template = 'templates/frontend';

    protected $settings = array();
    protected $site_name = '';
    // Кэш городов
    protected $cities_cache;

    // Кэщ географических параметров, города, округи и станции метро
    protected $_geo_params;
    /*
     * ID выбранных городов
     * all - все
     * и т.д.
     */
    protected $_selected_city_ids = array(
        'all'      => NULL,
        'auto'     => NULL,
        'work'     => NULL,
        'metro'    => NULL,
        'district' => NULL
    );
    function before()
    {
        parent::before();
        $this->response->headers('Pragma', 'no-cache');

        $this->settings = Kohana::$config->load('settings');
        $this->site_name = $this->settings['site_name'];
        // Стили
        $this->add_css('assets/css/reset.css');

        $this->add_css('assets/js/multiselect/jquery.multiselect.css');
        $this->add_css('assets/js/gallery/css/jquery.lightbox-0.5.css');
        $this->add_css('assets/css/rmsform.css');
        $this->add_css('assets/js/css/flick/jquery-ui-1.8.16.custom.css');
        $this->add_css('assets/css/bootstrap_frontend.css');
        $this->add_css('assets/css/main_min.css?version=781');
        // Скрипты

        $this->add_js('assets/js/jquery-1.7.1.js');
        $this->add_js('assets/js/jquery-ui-1.8.17.custom.min.js');

        $this->add_js('assets/js/jquery.columnizer.min.js');



        $this->add_js('assets/js/jquery.scrollTo-min.js');

        $this->add_js('assets/js/jquery.showpassword.js');
        $this->add_js('assets/js/ckeditor/ckeditor.js');
        $this->add_js('assets/js/multiselect/jquery.multiselect.js');
        $this->add_js('assets/js/multiselect/jquery.multiselect.filter.js');
        $this->add_js('assets/js/gallery/jquery.lightbox-0.5.js');
        $this->add_js('assets/js/jquery.qtip-1.0.0-rc3.js');


        $this->add_js('assets/js/main.js?version=780');

        $this->add_js('http://userapi.com/js/api/openapi.js?48', 'before');
        
        // Count of notices
        if ($this->user)
        {
            $this->template->notice_count = ORM::factory('notice')->get_notices_count($this->user);
        }


        $this->template->css_class = '';
        $this->template->bc['/'] = 'Главная';
        //$current_city = $this->session->get('current_city', 'moscow');
        $items = array(
            'cars' => array(
                'name' => 'cars',
                'ids'  => NULL
            ),
            'works' => array(
                'name' => 'works',
                'ids'  => NULL
            )
        );

        $this->_geo_params = Geography::get_geography_params();





        $this->_selected_city_ids = Geography::set_selected_city_id($this->_geo_params['cities'], Cookie::get('filter_city'));


        // Cities select on header
        $this->template->cities = $this->_geo_params['cities']['total'];
        uasort($this->template->cities, array('Model_City', 'cmp'));
        $this->template->selected_city_ids = array(
            'total'  => $this->_selected_city_ids['total'],
            'has_cars' => $this->_selected_city_ids['has_cars']
        );
	    $this->template->assoc_news = ORM::factory('newsportal');
    }
    protected function add_js($file, $disposition = 'after')
   	{
   		$this->template->scripts[$disposition][] = array(
   			'file' => $file,
   			'type' => 'text/javascript'
   		);
   	}
    function after()
    {
        parent::after();
    }


}