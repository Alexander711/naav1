<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Item_CarBrand extends Controller_Backend
{

    public function before()
    {
        parent::before();
        $this->template->bc['admin/item/carbrand'] = 'Марки автомобилей';
    }
    /**
     * Обзор марок авто
     * @return void
     */
    public function action_index()
    {
        $car = ORM::factory('car_brand');
        $this->view = View::factory('backend/item/carbrand/all')
                          ->set('car', $car);
        $this->template->title = 'Марки автомобилей';
        $this->template->content = $this->view;
    }
    /**
     * Добавление марки авто
     * @return void
     */
    public function action_add()
    {
        if ($_POST)
        {
            try
            {
                $car = ORM::factory('car_brand');
                $car->values($_POST, array('name', 'name_ru'));
                $car->save($car->upload_images());
                foreach (DB::select('city_id')->from('content_works')->distinct('city_id')->execute() as $city_id)
                {
                    DB::insert('content_works', array('work_id', 'city_id'))->values(array($car->id, $city_id))->execute();
                }


                Message::set(Message::SUCCESS, 'Марка автомобиля "'.$car->name.'" добавлена');
                $this->request->redirect('admin/item/carbrand');

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
     * Редактирование марки авто
     * @return void
     */
    public function action_edit()
    {
        $car = ORM::factory('car_brand', $this->request->param('id', NULL));
        if (!$car->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'carbrand_not_found'));
            $this->request->redirect('admin/item/carbrand');
        }
        if (Request::current()->method() == Request::POST)
        {
            try
            {

                $car->values($_POST, array('name', 'name_ru'));
                $car->update($car->upload_images());

                if (Arr::get($_POST, 'edit_content'))
                {
                    $this->request->redirect('admin/content/cars/edit/'.$car->content->id);
                }
                else
                {
                    Message::set(Message::SUCCESS, 'Марка автомобиля "'.$car->name.'" отредактирована');
                    $this->request->redirect('admin/item/carbrand');
                }
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = array_merge($this->errors, $e->errors('models'));
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $car->as_array();
        }
        $this->view = View::factory('backend/item/carbrand/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('car', $car)
                          ->set('url', 'admin/item/carbrand/edit/'.$car->id);
        $this->template->title = 'Редактирование марки авто "'.$car->name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    public function action_delete()
    {
        $car = ORM::factory('car_brand', $this->request->param('id', NULL));
        if (!$car->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'carbrand_not_found'));
            $this->request->redirect('admin/item/carbrand');
        }
        $name = $car->name.' '.$car->name_ru;
        $services_count = count($car->services->find_all());
        if ($_POST)
        {
            if (Arr::get($_POST, 'submit'))
            {
                $car->remove('services');
                $car->content->delete();
                $car->delete();
                $msg = __('car_brand_delete_success', array(':name' => $name));
                if ($services_count > 0)
                    $msg .= __('car_brand_delete_success_services', array(':count' => $services_count));

                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect('admin/item/carbrand');
            }
            else
            {
                $this->request->redirect('admin/item/carbrand');
            }
        }
        $text = __('car_brand_delete', array(':name' => $name));
        if ($services_count > 0)
            $text .= __('car_brand_delete_services_count', array(':count' => $services_count));

        $this->template->title = 'Удаление марки автомобиля';
        $this->template->bc['#'] = $this->template->title;
        $this->view = View::factory('backend/delete')
                          ->set('from_url', 'admin/item/carbrand')
                          ->set('url', $this->request->url())
                          ->set('title', $this->template->title)
                          ->set('text', $text);

        $this->template->content = $this->view;
    }


}