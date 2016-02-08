<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_CarModel extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/item/carmodel'] = 'Модели авто';
    }
    /**
     * Обзор моделей машин
     * @return void
     */
    public function action_index()
    {
        $carmodel = ORM::factory('car_model');
        $this->view = View::factory('backend/item/carmodel/all')
                          ->set('model', $carmodel);
        $this->template->title = 'Модели авто';
        $this->template->content = $this->view;
    }
    /**
     * Добавление модели машины
     * @return void
     */
    public function action_add()
    {
        if ($_POST)
        {
            try
            {
                $model = ORM::factory('car_model');
                $model->values($_POST, array('name', 'name_ru'));
                $model->save();
                Message::set(Message::SUCCESS, 'Модель автомобиля "'.$model->name.'" добавлена');
                $this->request->redirect('admin/item/carmodel');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/item/carbrand/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('url', 'admin/item/carbrand/add');
        $this->template->title = 'Добавление марки авто';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Редактирование модели машины
     * @return void
     */
    public function action_edit()
    {

    }
}