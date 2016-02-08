<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Service_Stock extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/service/stock'] = 'Акции';
    }
    /**
     * Обзор акций
     * @return void
     */
    public function action_index()
    {
        $stock = ORM::factory('stock');
        $this->view = View::factory('backend/stock/all')
                          ->set('stock', $stock);
        $this->template->title = 'Обзор акций';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование акции
     * @return void
     */
    public function action_edit()
    {
        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'stock_not_found'));
            $this->request->redirect('admin/service/stock');
        }
        if ($_POST)
        {
            try
            {
                $stock->values($_POST, array('text'));
                $stock->active = Arr::get($_POST, 'active', 0);
                $stock->update();
                Message::set(Message::SUCCESS, 'Акция отредактиована');
                $this->request->redirect('admin/service/stock');

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
        $this->view = View::factory('backend/stock/form')
                          ->set('url', 'admin/service/stock/edit/'.$stock->id)
                          ->set('errors', $this->errors)
                          ->set('values', $this->values);
        
        $this->template->title = 'Редактирования акции';
        $this->template->bc['#'] = 'Редактирования акции '.$stock->service->name;
        $this->template->content = $this->view;
    }
    /**
     * Активация акции
     * @return void
     */
    public function action_activate()
    {
        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'stock_not_found'));
            $this->request->redirect('admin/service/stock');
        }
        if ($stock->active == 1)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'stock_is_active'));
            $this->request->redirect('admin/service/stock');
        }
        DB::update('stocks')->set(array('active' => 1))->where('id', '=', $stock->id)->execute();

        Message::set(Message::SUCCESS, 'Акция компании '.$stock->service->name.' активирована');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Деактивация акции
     * @return void
     */
    public function action_deactivate()
    {

        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'stock_not_found'));
            $this->request->redirect('admin/service/stock');
        }
        if ($stock->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'stock_is_unactive'));
            $this->request->redirect('admin/service/stock');
        }
        DB::update('stocks')->set(array('active' => 0))->where('id', '=', $stock->id)->execute();

        Message::set(Message::SUCCESS, 'Акция компании '.$stock->service->name.' деактивирована');
        $this->request->redirect($this->request->referrer());
    }
    /**
     * Удаление акции
     * @return void
     */
    public function action_delete()
    {
        $stock = ORM::factory('stock', $this->request->param('id', NULL));
        if (!$stock->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'stock_not_found'));
            $this->request->redirect('admin/service/stock');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/service/stock');
            }
            if ($action['submit'])
            {
                $name = $stock->service->name;
                $stock->delete();
                Message::set(Message::SUCCESS, 'Акция компании '.$name.' удалена');
                $this->request->redirect('admin/service/stock');
            }
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/service/stock/delete/'.$stock->id)
                          ->set('text', 'Вы действительно хотите удалить акцию компании '.$stock->service->name.'?');
        $this->template->title = 'Удаление акции';
        $this->template->bc['#'] = $this->template->title.' '.$stock->service->name;
        $this->template->content = $this->view;
    }
}