<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Work extends ORM
{
    protected $_table_name = 'works';
    protected $_table_columns = array(
        'id' => NULL,
        'name' => NULL,
        'category_id' => NULL
    );
    protected $_has_many = array(
        'services' => array(
            'model' => 'service',
            'through' => 'services_works'
        ),
        'questions' => array(
            'model' => 'question',
            'through' => 'questions_works'
        ),
        'contents' => array(
            'model' => 'content_works',
            'foreign_key' => 'work_id'
        )
    );
    protected $_belongs_to = array(
        'category' => array(
            'model' => 'workcategory',
            'foreign_key' => 'category_id'
        )
    );

    protected $_reload_on_wakeup = FALSE;

    private $_params = array();
    private $_has_items = array();

    public function rules()
    {
        return array(
            'name' => array(array('not_empty')),
            'category_id' => array(
                array('not_empty'),
                array(
                    array($this, 'available_category'),
                    array(':value')
                ),
            )
        );
    }
    public function available_category($value)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))
                        ->from('work_categories')
                        ->where('id', '=', $value)
                        ->execute()->get('total_count');
    }
    public function set_params(Array $params)
    {
        $this->_params = $params;
        return $this;
    }
    public function set_has_items(Array $items)
    {
        $this->_has_items = $items;
        return $this;
    }

    public function get_works($witch_category = FALSE, $current_id = NULL, Array $cars = NULL)
    {
        $this->join(array('services_works', 'services_works'))
             ->on('services_works.work_id', '=', 'work.id')
             ->join(array('services', 'service'))
             ->on('service.id', '=', 'services_works.service_id');
        foreach ($this->_params as $param)
        {
            if ($param['value'])
                $this->and_where('service.'.$param['field'], $param['op'], $param['value']);
        }
        if ($cars)
            $this->join(array('services_cars', 'cars'))
                 ->on('service.id', '=', 'cars.service_id')
                 ->where('cars.car_id', 'in', $cars);

        $categories = array();
        if ($witch_category)
        {
            $query = DB::select('id', 'name')->from('work_categories');
            foreach ($query->execute() as $q)
            {
                $categories[$q['id']] = $q['name'];
            }
        }

        $works = array();
        foreach ($this->find_all() as $w)
        {
            if ($witch_category)
            {
                if (isset($categories[$w->category_id]))
                {
                    $works[$categories[$w->category_id]][$w->id] = array('name' => $w->name);
                    if ($current_id = $w->id)
                        $works[$categories[$w->category_id]][$w->id]['current_object'] = $w;
                    continue;
                }
            }
            $works[$w->id] = $w->name;
        }
        return $works;
    }
    /**
     * Вытаскиваем текущую услуги из массива категорий услуг
     * @param $categories
     * @param $work_id
     * @return null
     */
    public function extract_current_work_from_categories($categories, $work_id)
    {
        foreach($categories as $category)
        {
            if (array_key_exists($work_id, $category))
                return $category[$work_id];
        }
        return NULL;
    }
}