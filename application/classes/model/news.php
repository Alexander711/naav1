<?php
defined('SYSPATH') or die('No direct script access.');
class Model_News extends ORM
{
    private $_original_words = array();
    public $companies;
    public $portal;
    public $world;
    protected $_table_name = 'news_portal';
    /**
     * Строковой поиск новостей, любых.
     * @param $words
     * @param $original_words
     * @return Model_NewsPortal
     */
    public function search($words, $original_words)
    {
        if (empty($words))
            return FALSE;

        $this->_original_words = $original_words;
        $this->portal = ORM::factory('newsportal');
        $this->companies = ORM::factory('newsservice');
        $this->world = ORM::factory('newsworld');

        foreach ($words as $word)
        {
            $this->portal->or_where('title', 'LIKE', '%'.$word.'%')
                   ->or_where('text', 'LIKE', '%'.$word.'%');
            $this->companies->or_where('title', 'LIKE', '%'.$word.'%')
                    ->or_where('text', 'LIKE', '%'.$word.'%');
            $this->world->or_where('title', 'LIKE', '%'.$word.'%')
                  ->or_where('text', 'LIKE', '%'.$word.'%');
        }

        // Возвращаем новости
        return (object) array(
            'portal'  => $this->portal->order_by('date', 'DESC')->find_all(),
            'companies' => $this->companies->order_by('date_create', 'DESC')->find_all(),
            'world'   => $this->world->order_by('date', 'DESC')->find_all()
        );
    }
    /**
     * Генерация слов по которым найдена новость
     * @param $news
     * @return $this
     */
    public function get_words($news)
    {
        $words = array();
        foreach ($this->_original_words as $word)
        {
            if (mb_strripos($news->title, $word) !== FALSE OR mb_strripos($news->text, $word) !== FALSE)
                $words[] = $word;
        }
        $words = array_unique($words);
        return View::factory('frontend/search/search_words')->set('words', $words);
    }
}