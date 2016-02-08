<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Development_Dispute extends Controller_Backend
{
    public function action_index()
    {
        $this->request->redirect('admin/development');
    }
    public function action_add()
    {
        $task = ORM::factory('admin_task', $this->request->param('id', NULL));
        if (!$task->loaded())
        {
            Message::set(Message::ERROR, 'Не найдено такой задачи');
            $this->request->redirect('admin/task');
        }
        if ($_POST)
        {
            $dispute = ORM::factory('admin_dispute')->values($_POST, array('text'));
            $dispute->date_create = Date::formatted_time();
            try
            {
                $dispute->save();
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/task/form_dispute')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Добавление дополнения к задаче';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    public function action_delete()
    {
        $dispute = ORM::factory('admin_dispute', $this->request->param('id', NULL));
        if (!$dispute->loaded())
        {
            Message::set(Message::ERROR, 'Такое дополнение не найдено');
            $this->request->redirect('admin/development');
        }
        $task_url = 'admin/development/task/view/'.$dispute->task->id;
        if ($_POST)
        {
            $actions = Arr::extract($_POST, array('submit', 'cancel'), FALSE);
            /*
            if ($actions['cancel'])
                $this->request->redirect('admin/development/task/view/'.$dispute->task->id);
            */
            if ($actions['submit'])
            {
                $dispute->delete();
                Message::set(Message::SUCCESS, 'Дополнение к задаче удалено');
                
            }
            $this->request->redirect($task_url);
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', $this->request->uri())
                          ->set('from_url', $task_url)
                          ->set('title', 'Удаление дополнения к задаче')
                          ->set('text', 'Вы действительно хотите удалить дополнение к задаче "'.$dispute->task->title.'"');
        $this->template->title = 'Удаление дополнения к задаче';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}