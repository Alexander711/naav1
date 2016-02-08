<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Stock extends ORM
{
    protected $_table_columns = array(
        'id'         => NULL,
        'hints'      => NULL,
        'user_id'    => NULL,
        'service_id' => NULL,
        'title'      => NULL,
        'text'       => NULL,
        'date'       => NULL,
        'active'     => NULL
    );
    protected $_belongs_to = array(
        'service' => array(
            'model' => 'service',
            'foreign_key' => 'service_id'
        ),
        'user' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        )
    );
    protected $_has_many = array(
        'visits' => array(
            'model' => 'visit',
            'through' => 'servicestocks_visits',
            'foreign_key' => 'stock_id',
            'far_key' => 'visit_id'
        ),
    );
    private $_original_words = array();

    /**
     * Строковой поиск акций
     * @param $words
     * @param $original_words
     * @return Model_NewsPortal
     */
    public function search($words, $original_words)
    {
        if (empty($words))
            return FALSE;
        $this->_original_words = $original_words;


        foreach ($words as $word)
        {
            $this->or_where('title', 'LIKE', '%'.$word.'%')
                 ->or_where('text', 'LIKE', '%'.$word.'%');
        }


        return $this->find_all();
    }
    /**
     * Генерация слов по которым найдена акция
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
    public function  rules()
    {
        return array(
            'text' => array(array('not_empty')),
            'service_id' => array(
                array('not_empty'),
                array(array($this, 'check_service'), array(':value'))
            )
        );

    }

    public function filters()
    {
        return array(
            'text' => array(
                array('trim')
            )
        );
    }
    public function site_search($words = array())
    {
        if (empty($words))
        {
            return FALSE;
        }
        $this->where('active', '=', 1);
        $this->and_where_open();
        foreach ($words as $w)
        {
            $this->or_where('text', 'LIKE', '%'.$w.'%');
        }
        $this->and_where_close();
        return $this;
    }
    /**
     * Проверка на валидность указываемого сервиса.
     * Пользователь может создать акцию только к своим сервисам.
     * Исключение админ
     * @return void
     */
    public function check_service($value)
    {
        $user = Auth::instance()->get_user();
        if (Auth::instance()->logged_in('admin'))
        {
            return TRUE;
        }
        if (!$user OR DB::select('user_id')->from('services')->where('id', '=', $value)->execute()->get('user_id') != $user->id)
        {
            return FALSE;
        }
        return TRUE;
    }
    /**
     * Получаем все акции
     * @param string $date_sort
     * @return Database_Result
     */
    public function get_stocks($date_sort = 'DESC')
    {
        return $this->where('active', '=', 1)->order_by('date', $date_sort)->find_all();
    }
    /**
     * Получаем определенную акцию
     * @param $id
     * @return ORM
     */
    public function get_stock($id)
    {
        return $this->where('id', '=', $id)->where('active', '=', 1)->find();
    }
}