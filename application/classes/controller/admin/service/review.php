<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Service_Review extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/services'] = 'сервисы';
        $this->template->bc['admin/service/review'] = 'Отзывы';
    }
    /**
     * Обзор отзывов
     * @return void
     */
    public function action_index()
    {
        $review = ORM::factory('review');
        $this->view = View::factory('backend/review/all')
                          ->set('review', $review);
        $this->template->title = 'Отзывы';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование отзыва
     * @return void
     */
    public function action_edit()
    {
        $review = ORM::factory('review', $this->request->param('id', NULL));
        if (!$review->loaded())
        {
            Message::set(Message::ERROR, 'Такой отзыв не найден');
            $this->request->redirect('admin/service/review');
        }
        if ($_POST)
        {
            try
            {
                $review->values($_POST, array('text'));
                $review->active = Arr::get($_POST, 'active', 0);
                $review->update();
                Message::set(Message::SUCCESS, 'Отзыв к компании '.$review->service->name.' успешно отредактирован');
                $this->request->redirect('admin/service/review');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $review->as_array();
        }
        $this->view = View::factory('backend/review/form')
                          ->set('url', 'admin/service/review/edit/'.$review->id)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Редактирования отзыва к '.$review->service->name;
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Активация отзыва
     * @return void
     */
    public function action_activate()
    {
        $review = ORM::factory('review', $this->request->param('id', NULL));
        if (!$review->loaded())
        {
            Message::set(Message::ERROR, 'Такой отзыв не найден');
            $this->request->redirect('admin/service/review');
        }
        if ($review->active == 1)
        {
            Message::set(Message::ERROR, 'Отзыв итак активен');
            $this->request->redirect('admin/service/review');
        }
        DB::update('reviews')->set(array('active' => 1))->where('id', '=', $review->id)->execute();
        Message::set(Message::SUCCESS, 'Отзыв активирован');
        $this->request->redirect('admin/service/review');
    }
    /**
     * Деактивация отзыва
     * @return void
     */
    public function action_deactivate()
    {
        $review = ORM::factory('review', $this->request->param('id', NULL));
        if (!$review->loaded())
        {
            Message::set(Message::ERROR, 'Такой отзыв не найден');
            $this->request->redirect('admin/service/review');
        }
        if ($review->active == 0)
        {
            Message::set(Message::ERROR, 'Отзыв итак неактивен');
            $this->request->redirect('admin/service/review');
        }
        DB::update('reviews')->set(array('active' => 0))->where('id', '=', $review->id)->execute();
        Message::set(Message::SUCCESS, 'Отзыв деактивирован');
        $this->request->redirect('admin/service/review');
    }
    /**
     * Удаление отзыва
     * @return void
     */
    public function action_delete()
    {
        $review = ORM::factory('review', $this->request->param('id', NULL));
        if (!$review->loaded())
        {
            Message::set(Message::ERROR, 'Такой отзыв не найден');
            $this->request->redirect('admin/service/review');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/service/review');
            }
            if ($action['submit'])
            {
                $service_name = $review->service->name;
                $review->delete();
                Message::set(Message::SUCCESS, 'Отзыв для компании "'.$service_name.'" удален');
                $this->request->redirect('admin/service/review');
            }
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/service/review/delete/'.$review->id)
                          ->set('text', 'Вы действительно хотите удалить отзыв к компании "'.$review->service->name.'"');
        $this->template->title = 'Удаление отзыва к "'.$review->service->name.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}