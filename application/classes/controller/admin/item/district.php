<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_District extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->_base_url = 'admin/item/district';
        $this->template->bc[$this->_base_url] = 'Округи';
    }
    public function action_index()
    {
        $district = ORM::factory('district');
        $this->template->title = $this->template->bc[$this->_base_url];
        $this->view = View::factory('backend/item/district/all')
                          ->set('district', $district);
        $this->template->content = $this->view;
    }
    public function action_add()
    {
        $cities = ORM::factory('city')->get_all_cities();
        if ($_POST)
        {
            $district = ORM::factory('district')->values($_POST, array('name', 'full_name', 'abbreviation', 'city_id'));
            try
            {
                $district->save();
                $district->content->district_id = $district->id;
                $district->content->save();
                $city_filter_content = ORM::factory('content_filter')
                                          ->where('city_id', '=', $district->city_id)
                                          ->and_where('type', '=', 'district')
                                          ->find();
                if (!$city_filter_content->loaded())
                {
                    $city_filter_content->disable_validation = TRUE;
                    $city_filter_content->type = 'district';
                    $city_filter_content->city_id = $district->city_id;
                    $city_filter_content->save();
                }


                if (Arr::get($_POST, 'edit_content'))
                    $this->request->redirect('admin/content/district/edit/'.$district->content->id);

                $msg = __(Kohana::message('admin', 'district.add_success'), array(':name' => $district->name));
                $msg .= __(Kohana::message('admin', 'district.editing'), array(':content_id' => $district->content->id));
                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect($this->_base_url);

            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Добавление округа';
        $this->view = View::factory('backend/item/district/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('cities', $cities);
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    public function action_edit()
    {
        $district = $this->get_orm_model('district', $this->_base_url);
        $cities = ORM::factory('city')->get_all_cities();
        $name = $district->name;
        $this->values = $district->as_array();
        if ($_POST)
        {
            $district->values($_POST, array('name', 'full_name', 'abbreviation', 'city_id'));
            try
            {
                $district->update();
                if (Arr::get($_POST, 'edit_content'))
                    $this->request->redirect('admin/content/district/edit/'.$district->content->id);

                $msg = __(Kohana::message('admin', 'district.edit_success'), array(':name' => $name));
                $msg .= __(Kohana::message('admin', 'district.editing'), array(':content_id' => $district->content->id));
                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect($this->_base_url);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Редактирование округа "'.$name.'"';
        $this->view = View::factory('backend/item/district/form')
                          ->set('title', $this->template->title)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('cities', $cities);
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    public function action_delete()
    {
        $district = $this->get_orm_model('district', $this->_base_url);
        $services = $district->services->find_all();
        if ($_POST)
        {
            if (Arr::get($_POST, 'submit'))
            {
                $msg = __(Kohana::message('admin', 'district.delete_success'), array(':name' => $district->name));

                $query = DB::update('services')->set(array('district_id' => NULL))->where('district_id', '=', $district->id)->execute();
                $district->content->delete();
                $district->delete();

                if (count($services) > 0)
                    $msg .= __(Kohana::message('admin', 'metro.delete_success_services'), array(':count' => count($services)));

                Message::set(Message::SUCCESS, $msg);
            }
            $this->request->redirect($this->_base_url);
        }
        $text = __(Kohana::message('admin', 'district.delete_question'), array(':name' => $district->name));
        if (count($services) > 0)
            $text .= __(Kohana::message('admin', 'metro.delete_question_services'), array(':count' => count($services)));

        $this->template->title = 'Удаление округа "'.$district->name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/delete')
                          ->set('from_url', $this->_base_url)
                          ->set('title', $this->template->title)
                          ->set('text', $text);
        $this->template->content = $this->view;
    }
}