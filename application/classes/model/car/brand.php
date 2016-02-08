<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Car_Brand extends ORM
{
    protected $_table_name = 'car_brands';
    
    protected $_table_columns = array(
        'id'             => NULL,
        'name'           => NULL,
        'name_ru'        => NULL,
        'img_path'       => NULL,
        'thumb_img_path' => NULL
    );

    protected $_has_many = array(
        'services' => array(
            'model'       => 'service',
            'through'     => 'services_cars',
            'foreign_key' => 'car_id',
            'far_key'     => 'service_id',
        ),
        'contents' => array(
            'model'       => 'content_cars',
            'foreign_key' => 'car_id'
        )
    );

    /*
    protected $_has_one = array(
        'content' => array(
            'model'       => 'content_cars',
            'foreign_key' => 'car_id'
        )
    );
    */
    private $_params = array();
    private $_has_items = array();

    const  IMG_PATH = 'assets/img/auto_logos/';
    const  THUMB_IMG_PATH = 'assets/img/auto_logos_thumbs/';

    public function rules()
    {
        return array(
            'name_ru' => array(array('not_empty')),
        );
    }
    public function filters()
    {
        return array(
            'name_ru' => array(array('trim')),
            'name' => array(array('trim')),
        );
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

    function get_cars($current_id = NULL, Array $works = NULL)
    {
        $this->join(array('services_cars', 'services_cars'))
             ->on('services_cars.car_id', '=', 'car_brand.id')
             ->join(array('services', 'service'))
             ->on('service.id', '=', 'services_cars.service_id');
        foreach ($this->_params as $param)
        {
            if ($param['value'])
                $this->and_where('service.'.$param['field'], $param['op'], $param['value']);
        }
        if ($works)
            $this->join(array('services_works', 'works'))
                 ->on('service.id', '=', 'works.service_id')
                 ->where('works.work_id', 'in', $works);

        //$this->group_by('name');
        $cars = array();
        foreach ($this->order_by('name_ru', 'ASC')->find_all() as $c)
        {
            $full_name = (!trim($c->name)) ? $c->name_ru : $c->name_ru.' ('.$c->name.')';
            $cars[$c->id] = array(
                'name'    => $c->name,
                'name_ru' => $c->name_ru,
                'full_name' => $full_name,
                'thumb' => $c->thumb_img_path
            );
            if ($current_id AND $current_id == $c->id)
                $cars[$c->id]['current_object'] = $c;
        }
    
        return $cars;
    }
    public function get_car_name($full = FALSE)
    {
        $name = $this->name_ru;
        if ($full)
            if (trim($this->name))
                $name .= ' ('.$this->name.')';
        return $name;
    }
    /**
     * Получаем марки авто
     * @return void
     */
    function get_cars_as_array()
    {
        $cars = array();
        foreach ($this->find_all() as $car)
        {
            $name = ($car->name == '') ? $car->name_ru : $car->name;
            $cars[$car->id] = $name;
        }
        return $cars;
    }
    public function upload_images()
    {
        $file_validation = Validation::factory($_FILES)
                                     ->rule('thumb_img_path', 'Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif')))
                                     ->rule('img_path', 'Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif')));
        if ($file_validation->check())
        {
            if ($_FILES['img_path']['size'] != 0 AND is_writable(self::IMG_PATH))
            {
                $image = Image::factory($_FILES['img_path']['tmp_name']);

                if ($image->height > 150)
                    $image->resize(NULL, 150);

                if ($image->width > 300)
                    $image->resize(250, NULL);

                if (file_exists($this->img_path))
                    unlink($this->img_path);

                $file_path = self::IMG_PATH.md5($this->id.'_'.$this->name).'.'.File::ext_by_mime($image->mime);
                $image->save($file_path);
                $this->img_path = $file_path;
            }
            if ($_FILES['thumb_img_path']['size'] != 0 AND is_writable(self::THUMB_IMG_PATH))
            {
                $image = Image::factory($_FILES['thumb_img_path']['tmp_name']);

                if ($image->width > 30)
                    $image->resize(30, NULL);
                if ($image->height > 35)
                    $image->resize(NULL, 35);

                if (file_exists($this->thumb_img_path))
                    unlink($this->thumb_img_path);

                $file_path = self::THUMB_IMG_PATH.md5($this->id.'_'.$this->name).'_thumb.'.File::ext_by_mime($image->mime);
                $image->save($file_path);
                $this->thumb_img_path = $file_path;
            }

            $this->update();

        }
        return $file_validation;
    }
    /**
     * Сортирует авто и услуги для вывода на главной
     * @param array $models_input
     * @param array $works_input
     * @return array
     */
    public function sort_models_works($models_input = array(), $works_input = array())
    {
        $result = array(
            // Car brands
            0 => array(),
            // Works
            1 => array()
        );
        $settings = Kohana::$config->load('settings');

        $models = array_intersect($models_input, $settings['fast_filter_models']);
        if (count($models) > $settings['max_models'])
        {
            for ($i = 0; $i <= count($models) - $settings['max_models']; $i++)
            {
                array_pop($models);
            }
        }
        elseif (count($models) < $settings['max_models'])
        {
            $unused = array_diff($models_input, $models);
            foreach ($unused as $id => $name)
            {
                $models[$id] = $name;
                if (count($models) == $settings['max_models'])
                {
                    break;
                }
            }
        }

        $works = array_intersect($works_input, $settings['fast_filter_works']);
        if (count($works) > $settings['max_works'])
        {
            for ($i = 0; $i <= count($works) - $settings['max_works']; $i++)
            {
                array_pop($works);
            }
        }
        elseif (count($works) < $settings['max_works'])
        {
            $unused = array_diff($works_input, $works);
            foreach ($unused as $id => $name)
            {
                $works[$id] = $name;
                if (count($works) == $settings['max_works'])
                {
                    break;
                }
            }
        }

        $result[0] = $models;
        $result[1] = $works;
        return $result;
    }

}