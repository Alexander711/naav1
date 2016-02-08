<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_Metro extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->_base_url = 'admin/item/metro';
        $this->template->bc[$this->_base_url] = 'Станции метро';
    }
    /**
     * Просмотр станций метро
     * @return void
     */
    public function action_index()
    {
        $types = array(
            'list' => array(
                'url_query' => '?type=list',
                'li_attrs'  => array(),
                'text'      => 'Список'
            ),
            'map' => array(
                'url_query' => '?type=map',
                'li_attrs'  => array(),
                'text'      => 'Карта'
            )
        );

        $city = ORM::factory('city');

        $cities = $city->distinct('id')
                       ->join(array('metro', 'metro'))
                       ->on('metro.city_id', '=', 'city.id');

        $city_id = $this->request->param('id', 1);


        $type = Arr::get($_GET, 'type', 'list');
        if (!array_key_exists($type, $types))
            $type = 'list';
        $types[$type]['li_attrs']['class'] = 'active';


        $current_city = ORM::factory('city', $city_id);

        $this->template->title = $this->template->bc[$this->_base_url];
        $this->view = View::factory('backend/item/metro/all')
                          ->set('cities', $cities)
                          ->set('current_city', $current_city)
                          ->set('view_type', $type)
                          ->set('types', $types)
                          ->set('city_id', $city_id)
                          ->set('metro', $current_city->metro);
        $this->template->content = $this->view;
    }

    public function action_add()
    {
        $cities = ORM::factory('city')->get_all_cities();
        if ($_POST)
        {
            $metro = ORM::factory('metro')->values($_POST, array('name', 'city_id'));
            try
            {
                $metro->save();
                $metro->content->metro_id = $metro->id;
                $metro->content->save();
                $city_filter_content = ORM::factory('content_filter')
                                          ->where('city_id', '=', $metro->city_id)
                                          ->and_where('type', '=', 'metro')
                                          ->find();
                if (!$city_filter_content->loaded())
                {
                    $city_filter_content->disable_validation = TRUE;
                    $city_filter_content->type = 'metro';
                    $city_filter_content->city_id = $metro->city_id;
                    $city_filter_content->save();
                }

                if (Arr::get($_POST, 'edit_content'))
                {
                    $this->request->redirect('admin/content/metro/edit/'.$metro->content->id);
                }
                else
                {
                    $msg = __(Kohana::message('admin', 'metro.add_success'), array(':name' => $metro->name));
                    $msg .= __(Kohana::message('admin', 'metro.editing'), array(':content_id' => $metro->content->id));
                    Message::set(Message::SUCCESS, $msg);
                    $this->request->redirect($this->_base_url);
                }

            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Добавление станции метро';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/metro/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('cities', $cities);
        $this->template->content = $this->view;
    }
    public function action_edit()
    {
        $metro = $this->get_orm_model('metro', $this->_base_url);
        $cities = ORM::factory('city')->get_all_cities();
        $old_name = $metro->name;
        $this->values = $metro->as_array();
        if ($_POST)
        {
            $metro->values($_POST, array('name', 'city_id'));
            try
            {
                $metro->update();
                if (Arr::get($_POST, 'edit_content'))
                {
                    $this->request->redirect('admin/content/metro/edit/'.$metro->content->id);
                }
                else
                {
                    $msg = __(Kohana::message('admin', 'metro.edit_success'), array(':name' => $metro->name));
                    $msg .= __(Kohana::message('admin', 'metro.editing'), array(':content_id' => $metro->content->id));
                    Message::set(Message::SUCCESS, $msg);
                    $this->request->redirect($this->_base_url);
                }

            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Редактирование станции метро "'.$old_name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/metro/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('cities', $cities);
        $this->template->content = $this->view;
    }
    public function action_delete()
    {
        $metro = $this->get_orm_model('metro', $this->_base_url);
        $old_name = $metro->name;
        $services = $metro->services->find_all();
        if ($_POST)
        {
            if (Arr::get($_POST, 'submit'))
            {
                $query = DB::update('services')->set(array('metro_id' => NULL))->where('metro_id', '=', $metro->id)->execute();
                $metro->content->delete();
                $metro->delete();
                $msg = __(Kohana::message('admin', 'metro.delete_success'), array(':name' => $old_name));
                if (count($services) > 0)
                    $msg .= __(Kohana::message('admin', 'metro.delete_success_services'), array(':count' => count($services)));
                Message::set(Message::SUCCESS, $msg);
            }
            $this->request->redirect($this->_base_url);
        }
        $text = __(Kohana::message('admin', 'metro.delete_question'), array(':name' => $old_name));
        if (count($services) > 0)
            $text .= __(Kohana::message('admin', 'metro.delete_question_services'), array(':count' => count($services)));

        $this->template->title = 'Удаление станции метро "'.$old_name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/delete')
                          ->set('from_url', $this->_base_url)
                          ->set('title', $this->template->title)
                          ->set('text', $text);
        $this->template->content = $this->view;
    }
    /**
     * Для обновления пощиций маркеров на карте
     */
    public function action_ajax_map_update()
    {
        if ($this->request->is_ajax())
        {
            $this->auto_render = FALSE;
            $metro_stations = Arr::get($_POST, 'metro_stations');
            if (!$metro_stations)
            {
                $result['error'] = 'Ошибка обновления карты';
                echo json_encode($result);
                return;
            }
            $updated_count = 0;
            foreach ($metro_stations as $metro)
            {
                $pairs = array();
                foreach ($metro as $field => $value)
                {
                    $pairs[$field] = $value;
                }
                unset($pairs['id']);
                $query = DB::update('metro')->set($pairs)->where('id', '=', $metro['id'])->execute();
                if ($query)
                {
                    $updated_count ++;
                }
            }
            Geography::update_geography_params();
            $result['msg'] = 'updated metro stations: '.$updated_count;
            echo json_encode($result);
        }
    }
}