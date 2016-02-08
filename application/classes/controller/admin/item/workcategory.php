<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_WorkCategory extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->_base_url = 'admin/item/work';
        $this->template->bc['admin/item/workcategory'] = 'Категории услуг';
    }
    public function action_index()
    {
        $category = ORM::factory('workcategory');
        $this->view = View::factory('backend/item/workcategory/all')
                          ->set('category', $category);
        $this->template->title = $this->template->bc['admin/item/workcategory'];
        $this->template->content = $this->view;
    }
    public function action_add()
    {
        if ($_POST)
        {
            $category = ORM::factory('workcategory');
            $category->name = Arr::get($_POST, 'name');
            try
            {
                $category->save();
                if (Arr::get($_POST, 'add_work'))
                {
                    $this->request->redirect('admin/item/work/add?category='.$category->id);
                }
                else
                {
                    Message::set(Message::SUCCESS, __(Kohana::message('admin', 'work_category.add_success'), array(':name' => $category->name)));
                    $this->request->redirect($this->_base_url);
                }
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Добавление категории';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/workcategory/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('title', $this->template->title);
        $this->template->content = $this->view;
    }
    public function action_edit()
    {
        $category = ORM::factory('workcategory', $this->request->param('id', NULL));
        if (!$category->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'work_category.not_found'));
            $this->request->redirect('admin/item/workcategory');
        }
        $this->values = $category->as_array();
        $old_name = $category->name;
        IF ($_POST)
        {
            $category->name = Arr::get($_POST, 'name');
            try
            {
                $category->update();
                if (Arr::get($_POST, 'add_work'))
                {
                    $this->request->redirect('admin/item/work/add?category='.$category->id);
                }
                else
                {
                    Message::set(Message::SUCCESS, __(Kohana::message('admin', 'work_category.edit_success'), array('name' => $old_name)));
                    $this->request->redirect($this->_base_url);
                }
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->template->title = 'Редактирование категории услуг "'.$old_name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/item/workcategory/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('title', $this->template->title);
        $this->template->content = $this->view;
    }
    public function action_delete()
    {
        $category = ORM::factory('workcategory', $this->request->param('id', NULL));
        if (!$category->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'work_category.not_found'));
            $this->request->redirect('admin/item/workcategory');
        }
        $old_name = $category->name;
        $works = $category->works->find_all();
        if ($_POST)
        {
            if (Arr::get($_POST, 'submit'))
            {
                foreach ($works as $work)
                {
                    $work->content->delete();
                    $work->delete();
                }
                $category->delete();
                $msg = __(Kohana::message('admin', 'work_category.delete_success'), array(':name' => $old_name));
                if (count($works) > 0)
                    $msg .= __(Kohana::message('admin', 'work_category.delete_success_works'), array(':count' => count($works)));
                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect('admin/item/workcategory');
            }
            else
            {
                $this->request->redirect('admin/item/workcategory');
            }
        }

        $this->template->title = 'Удаление категории услуг "'.$old_name.'"';
        $this->template->bc['#'] = $this->template->title;

        $text = __(Kohana::message('admin', 'work_category.delete_question'), array(':name' => $old_name));
        if (count($works) > 0)
            $text .= __(Kohana::message('admin', 'work_category.delete_question_works'), array(':count' => count($works)));

        $this->view = View::factory('backend/delete')
                          ->set('from_url', 'admin/item/workcategory')
                          ->set('url', $this->request->url())
                          ->set('title', $this->template->title)
                          ->set('text', $text);
        $this->template->content = $this->view;
    }
}