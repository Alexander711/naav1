<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Vacancy extends Controller_Cabinet
{
    function before()
    {
        parent::before();
        $this->template->bc['cabinet/vacancy'] = 'Вакансии';
    }
    function action_index()
    {
        $this->view = View::factory('frontend/cabinet/vacancy/all')
                          ->set('vacancy', $this->user->vacancies);
        $this->template->title = $this->site_name.'Вакансии автосервисов';
        $this->template->content = $this->view;

    }
    function action_add()
    {
        if ($this->user->services->count_all() == 0)
        {
            $this->template->content = 'Увы, у вас нет ни одной компании чтобы добавить вакансию. '.HTML::anchor('cabinet/company/add', 'Добавить компанию');
        }
        $services = array();
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }

        
        if ($_POST)
        {
            $vacancy = ORM::factory('vacancy');
            try
            {
                $vacancy->values($_POST, array('title', 'text', 'service_id'));
                $vacancy->user_id = $this->user->id;
                $vacancy->active = 1;
                $vacancy->date = Date::formatted_time();
                $vacancy->save();
                // Обновляем дату редактирования у компании
                DB::update('services')->set(array('date_edited' => Date::formatted_time()))->where('id', '=', $vacancy->service_id)->execute();
                Message::set(Message::SUCCESS, 'Вакансия успешно добавлена');
                $this->request->redirect('cabinet/vacancy');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/vacancy/form')
                          ->set('url', 'cabinet/vacancy/add')
                          ->set('services', $services)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = $this->site_name.'Добавление вакансии';
        $this->template->bc['#'] = 'Добавление вакансии';
        $this->template->content = $this->view;
    }
    function action_edit()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded() OR $vacancy->user_id != $this->user->id)
        {
            Message::set(Message::ERROR, Kohana::message('cabinet', 'vacancy_not_found'));
            $this->request->redirect('cabinet/vacancy');
        }
        $services = array();
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }
        if ($_POST)
        {
            try
            {
                $vacancy->values($_POST, array('title', 'text', 'service_id'));
                $vacancy->user_id = $this->user->id;
                $vacancy->active = 1;
                $vacancy->date = Date::formatted_time();
                $vacancy->update();
                // Обновляем дату редактирования у компании
                DB::update('services')->set(array('date_edited' => Date::formatted_time()))->where('id', '=', $vacancy->service_id)->execute();
                Message::set(Message::SUCCESS, 'Вакансия успешно отредактирована');
                $this->request->redirect('cabinet/vacancy');
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
        $this->view = View::factory('frontend/cabinet/vacancy/form')
                          ->set('url', 'cabinet/vacancy/edit/'.$vacancy->id)
                          ->set('services', $services)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = $this->site_name.'Редактирование вакансии';
        $this->template->bc['#'] = 'Редактирование вакансии';
        $this->template->content = $this->view;
    }
    function action_delete()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded() OR $vacancy->user_id != $this->user->id)
        {
            Message::set(Message::ERROR, Kohana::message('cabinet', 'vacancy_not_found'));
            $this->request->redirect('cabinet/vacancy');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('cabinet/vacancy');
            }
            if ($action['submit'])
            {
                $title = $vacancy->title;
                $service_name = $vacancy->service->name;
                $vacancy->delete();
                Message::set(Message::SUCCESS, 'Вакансия "'.$title.'" для фирмы "'.$service_name.'" удалена');
                $this->request->redirect('cabinet/vacancy');
            }
        }
        $this->view = View::factory('frontend/cabinet/delete')
                          ->set('url', 'cabinet/vacancy/delete/'.$vacancy->id)
                          ->set('text', 'Вы действительно хотите удалить вакансию '.$vacancy->title.' для фирмы '.$vacancy->service->name);
        $this->template->title = $this->site_name.'Удаление вакансии';
        $this->template->bc['#'] = 'Удаление вакансии';
        $this->template->content = $this->view;
    }
}