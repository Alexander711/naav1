<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Service_Vacancy extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/service/vacancy'] = 'Вакансии';
    }
    /**
     * Обзор вакансий
     * @return void
     */
    public function action_index()
    {
        $vacancy = ORM::factory('vacancy');
        $this->view = View::factory('backend/vacancy/all')
                          ->set('vacancy', $vacancy);
        $this->template->title = 'Вакансии';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование вакансии
     * @return void
     */
    public function action_edit()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'vacancy_not_found'));
            $this->request->redirect('admin/service/vacancy');
        }
        if ($_POST)
        {
            try
            {
                $vacancy->values($_POST, array('title', 'text'));
                $vacancy->active = Arr::get($_POST, 'active', 0);
                $vacancy->update();
                Message::set(Message::SUCCESS, 'Вакансия компании '.$vacancy->service->name.' успешно отредактирована');
                $this->request->redirect('admin/service/vacancy');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $vacancy->as_array();
        }
        $this->view = View::factory('backend/vacancy/form')
                          ->set('url', 'admin/service/vacancy/edit/'.$vacancy->id)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Редактирование вакансии';
        $this->template->bc['#'] = $this->template->title.' '.$vacancy->title.' компании '.$vacancy->service->name;
        $this->template->content = $this->view;
    }
    /**
     * Активация вакансии
     * @return void
     */
    public function action_activate()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'vacancy_not_found'));
            $this->request->redirect('admin/service/vacancy');
        }
        if ($vacancy->active == 1)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'vacancy_is_active'));
            $this->request->redirect('admin/service/vacancy');
        }
        DB::update('vacancies')->set(array('active' => 1))->where('id', '=', $vacancy->id)->execute();

        Message::set(Message::SUCCESS, 'Вакансия компании '.$vacancy->service->name.' активирована');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Деактивация вакансии
     * @return void
     */
    public function action_deactivate()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'vacancy_not_found'));
            $this->request->redirect('admin/service/vacancy');
        }
        if ($vacancy->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'vacancy_is_unactive'));
            $this->request->redirect('admin/service/vacancy');
        }
        DB::update('vacancies')->set(array('active' => 0))->where('id', '=', $vacancy->id)->execute();

        Message::set(Message::SUCCESS, 'Вакансия компании '.$vacancy->service->name.' деактивирована');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Удаление вакансии
     * @return void
     */
    public function action_delete()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'vacancy_not_found'));
            $this->request->redirect('admin/service/vacancy');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/service/vacancy');
            }
            if ($action['submit'])
            {
                $title = $vacancy->title;
                $name = $vacancy->service->name;
                $vacancy->delete();
                Message::set(Message::SUCCESS, 'Вакансия "'.$title.'" компании '.$name.' удалена');
                $this->request->redirect('admin/service/vacancy');
            }
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/service/vacancy/delete/'.$vacancy->id)
                          ->set('text', 'Вы действительно хотите удалить вакансию "'.$vacancy->title.'" компании '.$vacancy->service->name.'?');
        $this->template->title = 'Удаление вакансии';
        $this->template->bc['#'] = $this->template->title.' компании '.$vacancy->service->name;
        $this->template->content = $this->view;
    }
}