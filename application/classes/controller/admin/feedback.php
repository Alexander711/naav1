<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Feedback extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/feedback'] = 'Запросы пользователей';
    }
    /**
     * Обзор запросов
     * @return void
     */
    public function action_index()
    {
        $mode = $this->request->param('id', 1);
        $feedback = ORM::factory('feedback');
        switch ($mode)
        {
            // Стандартные запросы
            case 1:
                $feedback->where('type', '=', 1);
                //$view_url = 'admin_feedback'
                break;
            // Заявки на рекламу
            case 2:
                $feedback->where('type', '=', 2);
                break;
            // Архив
            case 'trash':
                $feedback->where('active', '=', 0);
                break;
        }
        $this->view = View::factory('backend/feedback/all')
                          ->set('feedback', $feedback);
        $this->template->content = $this->view;
    }
    /**
     * Просмотр запроса
     * @return void
     */
    public function action_view()
    {
        $feedback = ORM::factory('feedback', $this->request->param('id', NULL));
        if (!$feedback->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'feedback_not_found'));
            $this->request->redirect('admin/feedback');
        }
        /*
        switch ($feedback->type)
        {
            case 1:
                $this->view = View::factory('backend/feedback/view_feedback');
                break;
            case 2:
                $this->view = View::factory('backend/feedback/view_adv');
                break;
        }
        $this->view->set('feedback', $feedback);

        $this->template->title = $title_pie.' от пользователя '.$feedback->user->username;
        */
        $this->view = View::factory('backend/feedback/view')
                          ->set('feedback', $feedback);
        $title_pie = ($feedback->type == 1) ? 'Запрос' : 'Заявка на рекламу';
        $this->template->title = $title_pie.' от пользователя '.$feedback->user->username;
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Отправка запроса в архив
     * @return void
     */
    public function action_to_trash()
    {

    }
    /**
     * Удаление запроса
     * @return void
     */
    public function action_delete()
    {

    }
}