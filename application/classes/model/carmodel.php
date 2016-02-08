<?php
defined('SYSPATH') or die('No direct script access.');
class Model_CarModel extends ORM
{
    protected $_table_name = 'car_models';
    protected $_table_columns = array(
        'id'     => NULL,
        'car_id' => NULL,
        'name'   => NULL
    );
    protected $_belongs_to = array(
        'car' => array(
            'model' => 'carbrand',
            'foreign_key' => 'car_id'
        )
    );
    public function rules()
    {
        return array(
            'name' => array(array('not_empty')),
            'car_id' => array(
                array('not_empty'),
                array('digit')
            ),
        );
    }
    public function filters()
    {
        return array(
            'car_id' => array(array('trim')),
            'name' => array(array('trim')),
        );
    }
    /**
     * Получаем модели авто
     * @param null $car_id
     * @return array
     */
    function get_models($car_id = NULL)
    {
        $models = array();
        foreach ($this->find_all() as $model)
        {
            if ($car_id)
            {
                if ($model->car_id == $car_id)
                {
                    $models[$model->id] = $model->name;
                }
                continue;
            }
            else
            {
                $models[$model->id] = $model->name;
            }
        }
        return $models;
    }
}