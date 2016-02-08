<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Content_Filter extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/content/filter'] = 'Страницы фильтра';
    }
    /**
     * Обзор страниц фильтров
     * @return void
     */
    public function action_index()
    {
        $current_type = $this->request->param('id', 'auto');
        $types = array(
            'auto' => array(
                'name' => 'Страницы тегов марок автомобилей',
                'css_class' => ''
            ),
            'work' => array(
                'name' => 'Страницы тегов услуг',
                'css_class' => ''
             ),
            'metro' => array(
                'name' => 'Страницы тегов станций метро',
                'css_class' => ''
            ),
            'district' => array(
                'name' => 'Страницы тегов округов',
                'css_class' => ''
            )
        );
        $types[$current_type]['css_class'] = 'active';

        $content = ORM::factory('content_filter')->where('type', '=', $current_type);
        $this->view = View::factory('backend/content/filter/all')
                          ->set('types', $types)
                          ->set('content', $content);
        $this->template->title = 'Страницы фильтров';
        $this->template->content = $this->view;
    }
    /**
     * Редактирование страницы фильтра
     * @return void
     */
    public function action_edit()
    {
        $content = ORM::factory('content_filter', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/filter');

        }
        // Город страницы
        $city = $content->city->name;
        $type = __('filter_type_'.$content->type);;
        if ($_POST)
        {
            try
            {
                $content->text = Arr::get($_POST, 'text', NULL);
                $content->date_edited = Date::formatted_time();
                $content->update();
                Message::set(Message::SUCCESS, 'Страница фильтра для города '.$city.' успешно отредактирована');
                $this->request->redirect('admin/content/filter/index/'.$content->type);
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $content->as_array();
        }
        $this->view = View::factory('backend/content/filter/edit')
                          ->set('url', 'admin/content/filter/edit/'.$content->id)
                          ->set('city', $city)
                          ->set('type', $type)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Редактирование страницы фильтра для г. '.$city;
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
}