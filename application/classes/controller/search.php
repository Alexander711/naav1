<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Search extends Controller_Frontend
{
    private $_search_modes = array(
        'all',
        'services',
        'news',
        'stocks',
        'vacancies'
    );
    public function before()
    {
        parent::before();
        $this->_search_modes = array_flip($this->_search_modes);
        foreach ($this->_search_modes as $mode => $value)
            $this->_search_modes[$mode] = __('site_search_'.$mode);
    }
    function action_index()
    {


        // Views
        $result = NULL;

        // Search form
        $this->view = View::factory('frontend/search/form')
                         ->bind('values', $this->values)
                         ->bind('errors', $this->errors)
                         ->bind('search_modes', $this->_search_modes)
                         ->bind('result', $result);

        if (Request::current()->method() == Request::GET AND Arr::get($_GET, 'str'))
            $result = $this->_search($_GET);




        $this->template->content = $this->view;
        $this->template->title = 'Поиск по сайту';

        $this->template->bc['#'] = $this->template->title;



    }
    /**
     * Выпиливание слов короче 3 букв
     * @param $value
     * @return mixed
     */
    public static function clear_short_words($value)
    {
        if (mb_strlen($value) < 3)
            return NULL;
        else
            return $value;
    }
    /**
     * Поиск леммы
     * @static
     * @param $value
     * @return mixed
     */
    public static function stemm($value)
    {
        return (mb_strlen($value) > 3) ? Stemmer::getInstance()->stem_word($value) : $value;
    }
    /**
     * Прослойка поиска
     * @param array $data
     * @return array
     */
    private function _search(Array $data)
    {
        $result = NULL;
        $validation = Validation::factory($data)
                                ->rule('str', 'not_empty')
                                ->rule('str', 'min_length', array(':value', 4));

        if ($validation->check())
        {
            // Обработка строки
            $search_str = trim(preg_replace('/ +/  ', ' ', preg_replace('/[^A-ZА-ЯЁ0-9]+/ui', ' ', mb_strtolower($validation['str']))));

            // Выпиливание стоп-слов, слов короче 3 букв, не больше 7 слов
            $original_words = array_slice(array_diff(Arr::map('Controller_Search::clear_short_words', explode(' ', $search_str)), Kohana::$config->load('settings.search_stop_words')), 0, 7);
            // Поиск леммы
            $words = Arr::map('Controller_Search::stemm', $original_words);

            if (!empty($words))
            {

                // ORM object
                $company = ORM::factory('service')->search($words, $original_words);

                $news = ORM::factory('news')->search($words, $original_words);
                $stocks = ORM::factory('stock')->search($words, $original_words);
                $vacancies = ORM::factory('vacancy')->search($words, $original_words);

                $types = array();


                if (count($company->services) > 0)
                    $types['services'] = __('site_search_services');
                if (count($company->shops) > 0)
                    $types['shops'] = __('site_search_shops');
                if (count($news->companies) > 0)
                    $types['companies-news'] = __('services_news');
                if (count($news->world) > 0)
                    $types['news-world'] = __('world_news');
                if (count($news->portal) > 0)
                    $types['news-association'] = __('association_news');
                if (count($stocks) > 0)
                    $types['stocks'] = __('cb_stocks');
                if (count($vacancies) > 0)
                    $types['vacancies'] = __('cb_vacancies');

                $result = View::factory('frontend/search/result')
                              ->set('types', $types)
                              ->set('company', $company->company)
                              ->set('services', $company->services)
                              ->set('shops', $company->shops)
                              ->set('stocks', $stocks)
                              ->set('news', $news)
                              ->set('vacancies', $vacancies);

            }
            else
            {
                $this->errors[] = 'Ошибка поиска, возможно введены недопустимые слова';
            }
        }
        else
        {
            $this->errors = $validation->errors('search_on_site');
        }
        $this->values = $data;
        return $result;

    }
    public function action_ajax()
    {
        if ($this->request->is_ajax())
        {
            $this->auto_render = FALSE;
            $result = $this->_search($_POST);

            echo json_encode(array(
                'errors' => $this->errors,
                'body' => ($result) ? $result->render() : NULL
            ));
        }

    }
}