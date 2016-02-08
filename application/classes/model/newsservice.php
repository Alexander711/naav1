<?php
defined('SYSPATH') or die('No direct script access.');
class Model_NewsService extends ORM
{
    protected $_table_name = 'news_service';
    protected $_table_columns = array(
        'id'          => NULL,
        'hints'       => NULL,
        'user_id'     => NULL,
        'service_id'  => NULL,
        'image'       => NULL,
        'text'        => NULL,
        'date_create' => NULL,
        'date_edited' => NULL,
        'active'      => NULL,
        'title'       => NULL
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
            'model'       => 'visit',
            'foreign_key' => 'news_id',
            'far_key'     => 'visit_id',
            'through'     => 'servicenews_visits'
        )
    );
    public function rules()
    {
        return array(
            'title' => array(array('not_empty')),
            'text' => array(array('not_empty')),
            'date_create' => array(array('not_empty')),
            'service_id' => array(
                array('not_empty'),
                array(array($this, 'check_service'), array(':value'))
            )
        );
    }
    public function filters()
    {
        return array(
            'title' => array(array('trim')),
            'text' => array(
                array('trim')
            ),
            'date_create' => array(array('trim')),
            'date_edited' => array(array('trim')),
        );
    }

    public function site_search($words)
    {
        if (empty($words))
        {
            return FALSE;
        }
        $this->where('active', '=', 1);
        $this->and_where_open();
        foreach ($words as $w)
        {
            $this->or_where('title', 'LIKE', '%'.$w.'%')
                 ->or_where('text', 'LIKE', '%'.$w.'%');
        }
        $this->and_where_close();
        return $this;
    }
    /**
     * Проверка на валидность указываемого сервиса.
     * Пользователь может создать новость только к своим сервисам.
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
     * Получаем активные новости компаний
     * @param string $date_sort
     * @return Database_Result
     */
    public function get_news($date_sort = 'DESC')
    {
        return $this->where('active', '=', 1)->order_by('date_create', $date_sort)->find_all();
    }
}