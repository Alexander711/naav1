<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_News extends Controller_Cabinet
{
    private $_image_url = 'upload/attachments/news_service';

    public function before()
    {
        parent::before();
        $this->template->bc['cabinet/news'] = 'Новости моих автосервисов';
    }
    function action_index()
    {
        if ($this->user->services->count_all() == 0)
        {
            $this->view = 'Увы, у вас нет ни одной компании чтобы добавить новость. '.HTML::anchor('cabinet/company/add', 'Добавить компанию');
        }
        else
        {
            $this->view = View::factory('frontend/cabinet/news/all')
                              ->set('news', $this->user->news_service);
        }

        $this->template->title = $this->site_name.'Новости моих автосервисов';
        $this->template->content = $this->view;
    }
    function action_add()
    {
        if ($this->user->services->count_all() == 0)
        {
            $this->template->content = 'Увы, у вас нет ни одной компании чтобы добавить новость. '.HTML::anchor('cabinet/company/add', 'Добавить компанию');
            return;
        }
        $services = array();
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }

        if ($_POST)
        {
            $this->validation = Validation::factory($_FILES)
                                          ->rule('news_image', 'Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif')));
            
            $news = ORM::factory('newsservice');
            $current_time = Date::formatted_time();

            try
            {
                $news->values($_POST, array('title', 'text'));
                $news->service_id = Arr::get($_POST, 'service_id', NULL);
                $news->active = 1;
                $news->user_id = $this->user->id;
                $news->date_create = $current_time;
                $news->save($this->validation);

                // Image upload
                if ($this->validation->check() AND $_FILES['news_image']['size'] != 0)
                {
                    $file_name = MyHelper::get_file_name($_FILES['news_image']);
                    if (is_writable($this->_image_url))
                    {
                        Upload::save($_FILES['news_image'], $file_name, $this->_image_url);

                        $name_pies = explode('.', $this->_image_url.'/'.$file_name);
                        $image = Image::factory($this->_image_url.'/'.$file_name);
                        $image->resize(150, NULL);
                        $image->save();
                        $image->resize(70, NULL);
                        $image->save($name_pies[0].'_pict.'.$name_pies[1]);
                        $news->image = $this->_image_url.'/'.$file_name;
                        $news->update();
                    }
                }

                // Обновляем дату редактирования у компании
                DB::update('services')->set(array('date_edited' => $current_time))->where('id', '=', $news->service->id)->execute();
                Logger::write(Logger::ADD, 'Пользователь добавил новость '.HTML::anchor('news/'.$news->id, $news->title), $this->user);
                Message::set(Message::SUCCESS, 'Новость добавлена');
                $this->request->redirect('cabinet/news');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('frontend/cabinet/news/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('services', $services)
                          ->set('url', 'cabinet/news/add');

        $this->template->title = $this->site_name.'Добавление новости';
        $this->template->bc['#'] = 'Добавление новости';
        $this->template->content = $this->view;
    }
    function action_edit()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded() OR $news->user_id != $this->user->id)
        {
            Message::set(Message::ERROR, Kohana::message('cabinet', 'news_not_found'));
            $this->request->redirect('cabinet');
        }
        $services = array();
        foreach ($this->user->services->find_all() as $service)
        {
            $services[$service->id] = $service->name;
        }

        if ($_POST)
        {
            $this->validation = Validation::factory($_FILES)
                                          ->rule('news_image', 'Upload::type', array(':value', array('jpg', 'jpeg', 'png', 'gif')));
            // Date of edit
            $current_time = Date::formatted_time();

            try
            {
                $title = $news->title;


                $news->values($_POST, array('title', 'text'));
                $news->active = 1;
                $news->service_id = Arr::get($_POST, 'service_id', NULL);
                $news->date_edited = $current_time;
                $news->update($this->validation);

                // Image upload
                if ($this->validation->check() AND $_FILES['news_image']['size'] != 0)
                {
                    echo 1;
                    $file_name = MyHelper::get_file_name($_FILES['news_image']);
                    if (is_writable($this->_image_url))
                    {
                        Upload::save($_FILES['news_image'], $file_name, $this->_image_url);
                        $name_pies = explode('.', $this->_image_url.'/'.$file_name);
                        $image = Image::factory($this->_image_url.'/'.$file_name);
                        $image->resize(150, NULL);
                        $image->save();
                        $image->resize(70, NULL);
                        $image->save($name_pies[0].'_pict.'.$name_pies[1]);
                        $news->image = $this->_image_url.'/'.$file_name;
                        $news->update();
                    }
                }
                // Обновляем дату редактирования у компании
                DB::update('services')->set(array('date_edited' => $current_time))->where('id', '=', $news->service->id)->execute();
                Logger::write(Logger::EDIT, 'Пользователь отредактировал новость '.HTML::anchor('news/'.$news->id, $news->title), $this->user);
                Message::set(Message::SUCCESS, 'Новость "'.$title.'" для компании "'.$news->service->name.'" отредактирована');
                $this->request->redirect('cabinet/news');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $news->as_array();
        }
        $this->view = View::factory('frontend/cabinet/news/form')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('services', $services)
                          ->set('url', 'cabinet/news/edit/'.$news->id);
        
        $this->template->title = $this->site_name.'Редактирование новости "'.$news->title.'" для автосервиса "'.$news->service->name.'"';
        $this->template->bc['#'] = 'Редактирование новости';
        $this->template->content = $this->view;
    }
    function action_delete()
    {
        $news = ORM::factory('newsservice', $this->request->param('id', NULL));
        if (!$news->loaded() OR $news->user_id != $this->user->id)
        {
            Message::set(Message::ERROR, Kohana::message('cabinet', 'news_not_found'));
            $this->request->redirect('cabinet');
        }
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('cabinet/mews');
            }
            if ($action['submit'])
            {
                $title = $news->title;
                if ($news->image AND file_exists($news->image) AND is_writable($news->image))
                {
                    unlink($news->image);
                    unlink(MyHelper::get_image_pict_name($news->image));
                }
                $news->delete();

                Message::set(Message::SUCCESS, 'Новость "'.$title.'" удалена');
                $this->request->redirect('cabinet/news');
            }
        }
        $this->view = View::factory('frontend/cabinet/delete')
                          ->set('url', 'cabinet/news/delete/'.$news->id)
                          ->set('text', 'Вы действительно хотите удалить новость '.$news->title);
        $this->template->title = $this->site_name.'Удаление новости';
        $this->template->bc['#'] = 'Удаление новости';
        $this->template->content = $this->view;
    }
}
 
