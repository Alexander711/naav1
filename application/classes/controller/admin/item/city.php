<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_City extends Controller_Backend
{

    public function before()
    {
        parent::before();
        $this->_base_url = 'admin/item/city';
        $this->template->bc[$this->_base_url] = 'Города';
    }
    public function action_index()
    {
        $city = ORM::factory('city');
        $this->view = View::factory('backend/item/city/all')
                          ->set('city', $city);
        $this->template->title = $this->template->bc[$this->_base_url];
        $this->template->content = $this->view;
    }
    public function action_add()
    {
        if ($_POST)
        {
            $city = ORM::factory('city')->values($_POST, array('name', 'genitive_name', 'dativus_name'));
            try
            {
                $city->save();
                $query_work = DB::insert('content_filter', array('city_id', 'type'))->values(array($city->id, 'work'))->execute();
                $query_auto = DB::insert('content_filter', array('city_id', 'type'))->values(array($city->id, 'auto'))->execute();
                $msg = __(Kohana::message('admin', 'city.add_success'), array(':name' => $city->name));
                $msg .= __(Kohana::message('admin', 'city.success_work_car_content_urls'), array(':content_id_car' => $query_auto[0], ':content_id_work' => $query_work[0]));
                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect($this->_base_url);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Добавление города';
        $this->view = View::factory('backend/item/city/form')
                          ->set('title', $this->template->title)
                          ->set('metro_stations_count', 0)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);

        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    public function action_edit()
    {
        $city = $this->get_orm_model('city', $this->_base_url);
        $this->values = $city->as_array();
        $old_name = $city->name;
        if ($_POST)
        {
            $city->values($_POST, array('name', 'genitive_name', 'dativus_name'));
            $metro_count = count($city->metro->find_all());
            if ($metro_count > 0)
            {
                $this->validation = Validation::factory($_FILES)
                                              ->rule('metro_map','Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif')))
                                              ->rule('metro_map_clear', 'Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif')));
            }

            try
            {
                $city->update($this->validation);
                echo 1;
                foreach ($city->contents->find_all() as $content)
                {
                    $content_ids[$content->type] = $content->id;
                }
                if ($metro_count > 0 AND $this->validation->check())
                {
                    $hash_name = md5(date('YmdHis'));
                    foreach ($_FILES as $img_name => $image)
                    {
                        if (!$image['size'] > 0)
                            continue;
                        $exts = File::exts_by_mime($image['type']);
                        if ($exts[0] == 'jpe')
                            $exts[0] = 'jpeg';
                        $name = ($img_name == 'metro_map') ? $hash_name.'.'.$exts[0] : $hash_name.'_clear.'.$exts[0];
                        Upload::save($_FILES[$img_name], $name, 'assets/img/metro');
                        $path = 'assets/img/metro/'.$name;
                        echo $path;
                        $city_img_column = 'img_'.$img_name;
                        $image_gd = Image::factory($path);
                        if ($image_gd->width > 950)
                        {
                            $image_gd->resize(950, NULL);
                            $image_gd->save();
                        }
                        $city->$city_img_column = '/'.$path;
                    }
                    $city->update();
                 }

                $msg = __(Kohana::message('admin', 'city.edit_success'), array(':name' => $old_name));
                $msg .= __(Kohana::message('admin', 'city.success_work_car_content_urls'), array(':content_id_car' => $content_ids['auto'], ':content_id_work' => $content_ids['work']));

                if (array_key_exists('district', $content_ids))
                    $msg .= __(Kohana::message('admin', 'city.success_district_urls'), array(':content_id_district' => $content_ids['district']));
                if (array_key_exists('metro', $content_ids))
                    $msg .= __(Kohana::message('admin', 'city.success_metro_urls'), array(':content_id_metro' => $content_ids['metro']));

                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect($this->_base_url);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Редактирование города "'.$old_name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/city/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('metro_stations_count', count($city->metro->find_all()));
        $this->template->content = $this->view;
    }
}