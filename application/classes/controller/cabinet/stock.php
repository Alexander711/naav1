<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Stock extends Controller_Cabinet
{
    function before()
    {
        parent::before();
        $this->template->bc['cabinet/stock'] = 'Акции';
    }
    function action_index()
    {
        $stock = $this->user->stocks;
        $this->view = View::factory('frontend/cabinet/stock/all')
                          ->set('stock', $stock);

        $this->template->title = $this->site_name.'Акции';
        $this->template->content = $this->view;
    }
    function action_add()
    {
        if ($this->user->services->count_all() == 0)
        {
            $this->template->content = 'Увы, у вас нет ни одной компании чтобы добавить акцию. '.HTML::anchor('cabinet/company/add', 'Добавить компанию');
        }
        $services = array();
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }

        if ($_POST)
        {
            $stock = ORM::factory('stock');
            try
            {
                $stock->values($_POST, array('text', 'service_id'));
                $stock->user_id = $this->user->id;
                $stock->active = 1;
                $stock->date = Date::formatted_time();
                $stock->save();
                // Обновляем дату редактирования у компании
                DB::update('services')->set(array('date_edited' => Date::formatted_time()))->where('id', '=', $stock->service_id)->execute();
                Message::set(Message::SUCCESS, 'Акция добавлена');
                $this->request->redirect('cabinet/stock');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/stock/form')
                          ->set('url', 'cabinet/stock/add')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('services', $services);
        $this->template->title = $this->site_name.'Добавление акции';
        $this->template->bc['#'] = 'Добавление акции';
        $this->template->content = $this->view;
    }
    function action_edit()
    {
        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded() OR $stock->user_id != $this->user->id)
        {
            $this->request->redirect('cabinet/stock');
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
                $stock->values($_POST, array('text', 'service_id'));
                $stock->update();
                // Обновляем дату редактирования у компании
                DB::update('services')->set(array('date_edited' => Date::formatted_time()))->where('id', '=', $stock->service_id)->execute();                     
                Message::set(Message::SUCCESS, 'Акция отредактирована');
                $this->request->redirect('cabinet/stock');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $stock->as_array();
        }
        $this->view = View::factory('frontend/cabinet/stock/form')
                          ->set('url', 'cabinet/stock/edit/'.$stock->id)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('services', $services);
        $this->template->title = $this->site_name.'Редактирование акции';
        $this->template->bc['#'] = 'Редактирование акции';
        $this->template->content = $this->view;
    }
    function action_delete()
    {
        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded() OR $stock->user_id != $this->user->id)
        {
            $this->request->redirect('cabinet/stock');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('cabinet/stock');
            }
            if ($action['submit'])
            {
                $title = $stock->service->name;
                $stock->delete();
                Message::set(Message::SUCCESS, 'Акция для сервиса "'.$title.'" удалена');
                $this->request->redirect('cabinet/stock');
            }
        }
        $this->view = View::factory('frontend/cabinet/delete')
                          ->set('url', 'cabinet/stock/delete/'.$stock->id)
                          ->set('text', 'Вы действительно хотите удалить акцию для севриса '.$stock->service->name);
        $this->template->title = $this->site_name.'Удаление акции';
        $this->template->bc['#'] = 'Удаление акции';
        $this->template->content = $this->view;
    }
}