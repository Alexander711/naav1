<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_Work extends Controller_Backend
{
    public function before()
    {
        parent::before();

        $this->_base_url = 'admin/item/work';
        $this->template->bc['admin/item/work'] = 'Услуги';
    }
    public function action_index()
    {
        $work = ORM::factory('work');
        $this->template->title = $this->template->bc['admin/item/work'];
        $this->view = View::factory('backend/item/work/all')
                          ->set('work', $work);
        $this->template->content = $this->view;
    }
    public function action_add()
    {
        $category = ORM::factory('workcategory')->get_all_as_array();
        $this->values['category_id'] = Arr::get($_GET, 'category');
        if ($_POST)
        {
            $work = ORM::factory('work')->values($_POST, array('name', 'category_id'));
            try
            {
                $work->save();

                foreach (DB::select('city_id')->from('content_works')->distinct('city_id')->execute() as $city_id)
                {
                    DB::insert('content_works', array('work_id', 'city_id'))->values(array($work->id, $city_id))->execute();
                }


                Message::set(Message::SUCCESS, __(Kohana::message('admin', 'work.add_success'), array(':name' => $work->name)));
                $this->request->redirect($this->_base_url);

            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Добавление услуги';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/work/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('title', $this->template->title)
                          ->set('categories', $category);
        $this->template->content = $this->view;
    }
    public function action_edit()
    {
        $work = ORM::factory('work', $this->request->param('id', NULL));
        if (!$work->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'work.not_found'));
            $this->request->redirect($this->_base_url);
        }
        $category = ORM::factory('workcategory')->get_all_as_array();
        $this->values = $work->as_array();
        if ($_POST)
        {
            $work->values($_POST, array('name', 'category_id'));
            try
            {
                $work->update();
                if (Arr::get($_POST, 'edit_content'))
                {
                    $this->request->redirect('admin/content/works/edit/'.$work->content->id);
                }
                else
                {
                    Message::set(Message::SUCCESS, __(Kohana::message('admin', 'work.edit_success'), array(':name' => $work->name)));
                    $this->request->redirect($this->_base_url);
                }
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Редактирование услуги "'.$work->name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/work/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('title', $this->template->title)
                          ->set('categories', $category);
        $this->template->content = $this->view;
    }
    public function action_delete()
    {
        $work = ORM::factory('work', $this->request->param('id', NULL));
        if (!$work->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'work.not_found'));
            $this->request->redirect($this->_base_url);
        }
        $old_name = $work->name;
        $services = $work->services->find_all();
        if ($_POST)
        {
            if (Arr::get($_POST, 'submit'))
            {
                $work->content->delete();
                $work->remove('services');
                $work->delete();
                $msg = __(Kohana::message('admin', 'work.delete_success'), array(':name' => $old_name));
                if (count($services) > 0)
                    $msg .= __(Kohana::message('admin', 'work.delete_success_services'), array(':count' => count($services)));
                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect($this->_base_url);
            }
            else
            {
                $this->request->redirect($this->_base_url);
            }
        }
        $this->template->title = 'Удаление услуги "'.$old_name.'"';
        $this->template->bc['#'] = $this->template->title;
        $text = __(Kohana::message('admin', 'work.delete_question'), array(':name' => $old_name));
        if (count($services) >0 )
            $text = __(Kohana::message('admin', 'work.delete_question_services'), array(':count' => count($services)));

        $this->view = View::factory('backend/delete')
                           ->set('from_url', $this->_base_url)
                           ->set('title', $this->template->title)
                           ->set('text', $text);
        $this->template->content = $this->view;
    }
}