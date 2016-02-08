<?php
defined('SYSPATH') or die('No direct script access.');
class Model_NewsWorld extends ORM
{
    protected $_table_name = 'news_world';
    protected $_table_columns = array(
        'id'     => NULL,
        'title'  => NULL,
        'text'   => NULL,
        'date'   => NULL,
        'active' => NULL
    );
    public function rules()
    {
        return array(
            'title' => array(array('not_empty')),
            'text' => array(array('not_empty')),
            'date' => array(array('not_empty'))
        );
    }
    public function filters()
    {
        return array(
            'title' => array(array('trim')),
            'text' => array(array('trim')),
            'date' => array(array('trim'))
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
}