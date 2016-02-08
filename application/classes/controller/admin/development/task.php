<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Development_Task extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/development'] = 'План разработки';
    }
    /**
     * Все задачи
     * @return void
     */
    public function action_index()
    {
        $statuses = Kohana::$config->load('task.statuses');
        $task = ORM::factory('admin_task');
        $this->view = View::factory('backend/task/all')
                          ->set('task', $task)
                          ->set('statuses', $statuses);
        $this->template->title = 'План разработки';
        $this->template->content = $this->view;
    
    }
    /**
     * Просмотр задачи
     * @return void
     */
    public function action_view()
    {
        $task = ORM::factory('admin_task', $this->request->param('id', NULL));
        if (!$task->loaded())
        {
            Message::set(Message::ERROR, 'Не найдено такой задачи');
            $this->request->redirect('admin/development');
        }
        if ($_POST)
        {
            $dispute = ORM::factory('admin_dispute');
            $dispute->task_id = $task->id;
            $dispute->text = Arr::get($_POST, 'text');
            $dispute->date_create = Date::formatted_time();
            try
            {
                $dispute->save();
                Message::set(Message::SUCCESS, 'Дополнение к задаче успешно добавлено');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/task/view')
                          ->set('task', $task)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Просмотр задачи "'.$task->title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Добавление задачи
     * @return void
     */
    public function action_add()
    {
        $config = Kohana::$config->load('task');
        $statuses = $config['statuses'];
        $priorities = $config['priorities'];
        if ($_POST)
        {
            $task = ORM::factory('admin_task');
            $task->values($_POST, array('title', 'text', 'priority', 'status'));
            $task->date_create = Date::formatted_time();
            try
            {
                $task->save();
                Message::set(Message::SUCCESS, 'Новая задача добавлена');
                $this->request->redirect('admin/development');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/task/form')
                          ->set('url', 'admin/development/task/add')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('priorities', $priorities)
                          ->set('statuses', $statuses);
        $this->template->title = 'Добавлнение задачи';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Редактирование задачи
     * @return void
     */
    public function action_edit()
    {
        $task = ORM::factory('admin_task', $this->request->param('id', NULL));
        if (!$task->loaded())
        {
            Message::set(Message::ERROR, 'Не найдено такой задачи');
            $this->request->redirect('admin/development');
        }
        $config = Kohana::$config->load('task');
        $statuses = $config['statuses'];
        $priorities = $config['priorities'];
        $this->values = $task->as_array();
        if ($_POST)
        {
            $old_title = $task->title;
            $task->values($_POST, array('title', 'text', 'priority', 'status'));
            $task->date_edited = Date::formatted_time();
            try
            {
                $task->update();
                Message::set(Message::SUCCESS, 'Задача "'.$old_title.'" отредактирована');
                $this->request->redirect('admin/development');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/task/form')
                          ->set('url', $this->request->uri())
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('priorities', $priorities)
                          ->set('statuses', $statuses);
        $this->template->title = 'Редактирование задачи';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Удаление задачи
     * @return void
     */
    public function action_delete()
    {

    }


    public function action_choose_status()
    {
        $task = ORM::factory('admin_task', $this->request->param('id', NULL));
        $status = Arr::get($_GET, 'status', NULL);
        if (!$task->loaded())
        {
            Message::set(Message::ERROR, 'Не найдено такой задачи');
            $this->request->redirect('admin/development');
        }
        $statuses = Kohana::$config->load('task.statuses');
        if (!array_key_exists($status, $statuses))
        {
            Message::set(Message::ERROR, 'Неправильный статус');
            $this->request->redirect('admin/development');
        }
        $task->status = $status;
        $task->update();
        Message::set(Message::SUCCESS, 'Статус успешно сменен на "'.__($statuses[$status]['i18n']).'"');
        $this->request->redirect('admin/development');
    }
}