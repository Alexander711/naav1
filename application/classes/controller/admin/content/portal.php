<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Content_Portal extends Controller_Backend
{
    public function before()
    {
        parent::before();
        // Breadcrumbs
        $this->template->bc['admin/content/portal'] = 'Страницы сайта';
    }
    /**
     * Просмотр страниц
     * @return void
     */
    public function action_index()
    {
        $content = ORM::factory('content_site');

        $this->view = View::factory('backend/content/portal/all')
                          ->set('content', $content);
        $this->template->title = 'Страницы сайта';
        $this->template->content = $this->view;
    }
    /**
     * Добавление контента
     * @return void
     */
    public function action_add()
    {
        $this->values['active'] = 1;
        if ($_POST)
        {
            $content = ORM::factory('content_site');
            $active = Arr::get($_POST, 'active', 0);
            try
            {
                $content->values($_POST, array('title', 'url', 'keywords', 'description', 'text'));
                $content->active = $active;
                $content->date = Date::formatted_time();
                $content->save();
                $this->route_update();
                Message::set(Message::SUCCESS, 'Страница "'.$content->title.'" добавлена');
                $this->request->redirect('admin/content/portal');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->values = $_POST;
                $this->errors = $e->errors('models');
            }
        }

        $this->view = View::factory('backend/content/portal/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('url', 'admin/content/portal/add');
        $this->template->title = 'Добавление страницы';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Редактирование контента
     * @return void
     */
    public function action_edit()
    {
        $content = ORM::factory('content_site', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/portal');
        }

        $this->values = $content->as_array();

        if ($_POST)
        {
            $active = Arr::get($_POST, 'active', 0);
            try
            {
                $content->values($_POST, array('title', 'url', 'keywords', 'description', 'text'));
                $content->active = $active;
                $content->date = Date::formatted_time();
                $content->update();
                $this->route_update();
                Message::set(Message::SUCCESS, 'Страница "'.$content->title.'" отреакдтирована');
                $this->request->redirect('admin/content/portal');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->values = $_POST;
                $this->errors = $e->errors('models');
            }
        }

        $this->view = View::factory('backend/content/portal/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('url', 'admin/content/portal/edit/'.$this->request->param('id'));
        $this->template->title = 'Добавление страницы';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Удаление страницы
     * @return void
     */
    public function action_delete()
    {
        $content = ORM::factory('content_site', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/portal');
        }

        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/content/portal');
            }
            if ($action['submit'])
            {
                $title = $content->title;
                $content->delete();
                $this->route_update();
                Message::set(Message::SUCCESS, 'Страница "'.$title.'" удалена');
                $this->request->redirect('admin/content/portal');
            }
        }
        $this->view = View::factory('backend/delete')
                          ->set('url', $this->request->uri())
                          ->set('from_url', 'admin/content/portal')
                          ->set('title', 'Удаление страницы')
                          ->set('text', 'Вы действительно хотите удалить страницу "'.$content->title.'"?');
        $this->template->title = 'Удаление страницы "'.$content->title.'"';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Деактивация контента
     * @return void
     */
    public function action_deactivate()
    {
        $content = ORM::factory('content_site', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/portal');
        }
        if ($content->active == 0)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_is_unactive'));
            $this->request->redirect('admin/content/portal');
        }
        $content->active = 0;
        $content->update();
        $this->route_update();
        Message::set(Message::SUCCESS, 'Страница "'.$content->title.'" деактивирована');
        $this->request->redirect('admin/content/portal');
    }
    /**
     * Активация контента
     * @return void
     */
    public function action_activate()
    {
        $content = ORM::factory('content_site', $this->request->param('id', NULL));
        if (!$content->loaded())
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_not_found'));
            $this->request->redirect('admin/content/portal');
        }
        if ($content->active == 1)
        {
            Message::set(Message::ERROR, Kohana::message('admin', 'content_is_active'));
            $this->request->redirect('admin/content/portal');
        }
        $content->active = 1;
        $content->update();
        $this->route_update();
        Message::set(Message::SUCCESS, 'Страница "'.$content->title.'" активирована');
        $this->request->redirect('admin/content/portal');
    }
    /**
     * Запись активных URL-ов в конфиг
     * @return void
     */
    private function route_update()
    {
        $content = ORM::factory('content_site');
        $route = array();
        foreach ($content->where('active', '!=', 0)->find_all() as $c)
        {
            if ($c->url != 'home')
                $route[] = "'".$c->url."'";
        }
        $str = "<?php defined('SYSPATH') or die('No direct access allowed.');\n return array(\n";
        $str .= "\t'urls' => array(".implode(',', $route).")\n);";
        $file = fopen('application/config/pages.php', 'w');
        fputs($file, $str);
        fclose($file);
    }
}