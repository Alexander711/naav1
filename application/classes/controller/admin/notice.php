<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Notice extends Controller_Backend
{
    public function before()
    {
        parent::before();
        $this->template->bc['admin/notice'] = 'Уведомления';
    }
    /**
     * Уведомление для сервисов
     * @return void
     */
    public function action_index()
    {
        $notice = ORM::factory('notice')
                     ->where('type', '=', 'service')
                     ->order_by('date', 'DESC');
        $this->view = View::factory('backend/notice/service_all')
                          ->set('notice', $notice);
        $this->template->title = 'Уведомления';
        $this->template->content = $this->view;
    }
    /**
     * Уведомления для пользователей
     * @return void
     */
    public function action_user()
    {
		Message::set(Message::ERROR, 'Уведомления для пользователей отключено');
		$this->request->redirect('admin/notice');
        $notice = ORM::factory('notice')
                     ->where('type', '=', 'user')
                     ->order_by('date', 'DESC');
        $this->view = View::factory('backend/notice/user_all')
                          ->set('notice', $notice);
        $this->template->title = 'Уведомления для пользователей';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Системные уведомления
     * @return void
     */
    public function action_system()
    {
        $notice = ORM::factory('notice')
                     ->where('type', '!=', 'service')
                     ->and_where('type', '!=', 'user');
        $this->view = View::factory('backend/notice/system_all')
                          ->set('notice', $notice);
        $this->template->title = 'Системные уведомления';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }

    /**
     * Добавление уведомления для сервисов
     * @return void
     */
    public function action_add_service()
    {
        $notice = ORM::factory('notice');
        $notice->get_services_users_ids();

	    $city_list = ORM::factory('city');
	    $city_list_storage = array();
        foreach ($city_list->find_all() as $city) {

	        $city_list_storage[$city->id] = array(
                'city_name'     => $city->name,
                'list'          => array(),
            );
	        foreach ($city->services->find_all() as $s) {
		        $city_list_storage[$city->id]['list'][] = $s->id;
            }

	        $city_list_storage[$city->id]['list'] = implode(",",$city_list_storage[$city->id]['list']);
        }


        $this->values['service'][] = $this->request->param('id', NULL);
        if ($_POST)
        {
            $notice->services_list['selected_services'] = array_flip(Arr::get($_POST, 'service', array()));

            try
            {
                $notice->values($_POST, array('title', 'text'));
                $notice->type = 'service';
                $notice->date = Date::formatted_time();
                $notice->for = (!empty($notice->services_list['selected_services'])) ? 'optional' : 'all';
                $notice->save();

                if (!empty($notice->services_list['selected_services']))
                {
                    $notice->services_list['selected_services'] = array_intersect_key($notice->services_list['all_services'], $notice->services_list['selected_services']);
                    $services_ids = array_keys($notice->services_list['selected_services']);
                }
                else
                {
                    $services_ids = array_keys($notice->services_list['all_services']);
                }

                $notice->add('services', $services_ids);

                $mail_count = $notice->send_email();

                Message::set(Message::SUCCESS, 'Уведомление для след. кол-ва компаний успешно добавлено: '.count($services_ids)
                                              .'. След. кол-во писем отправлено в очередь на отправку: '.$mail_count['new'].'.');
                $this->request->redirect('admin/notice');


            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/notice/form_service')
                          ->set('url', 'admin/notice/add_service')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('services', $notice->services_list['all_services'])
                          ->set('city_list', $city_list_storage)
                          ->set('debug', $notice->services_list);

        $this->template->title = 'Добавление уведомления';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;

    }
    /**
     * Редактирование уведомлений для компаний
     * @return void
     */
    public function action_edit_service()
    {
        $notice = ORM::factory('notice', $this->request->param('id', NULL));
        if (!$notice->loaded() OR $notice->type != 'service')
        {
            Message::set(Message::ERROR, 'Такое уведомление не найдено, или не подходящий тип');
            $this->request->redirect('admin/notice');
        }
        $notice_count = array(
            'new'    => 0,
            'reread' => 0,
            'remove' => 0
        );
        $notice->get_services_users_ids();
        // Флаг для сохранения статусов прочел/не прочел
        $notice->save_statuses = Arr::get($_POST, 'save_status', FALSE);
        if ($_POST)
        {
            // Старый заголовок и текст
            $old_values = array(
                'title' => $notice->title,
                'text'  => $notice->text
            );

            $notice->services_list['selected_services'] = array_flip(Arr::get($_POST, 'service', array()));
            $notice->values($_POST, array('title', 'text'));
            $notice->date_edited = Date::formatted_time();
            $notice->for = (!empty($notice->services_list['selected_services'])) ? 'optional' : 'all';

            try
            {
                $notice->update();
                // Выбранные сервисы, но еще НЕДОБАВЛЕННЫЕ
                if (!empty($notice->services_list['selected_services']))
                {
                    // Выборка действительно имеющихся сервисов
                    $notice->services_list['selected_services'] = array_intersect_key($notice->services_list['all_services'], $notice->services_list['selected_services']);
                    // Выборка сервисов для удаления
                    $notice->services_list['to_remove_services'] = array_diff_key($notice->services_list['added_services'], $notice->services_list['selected_services']);
                    // Выборка сервисов для добавления, которые еще не добавлены
                    $notice->services_list['to_add_services'] = array_intersect_key($notice->services_list['selected_services'], $notice->services_list['not_added_services']);
                }
                else
                {
                    $notice->services_list['to_add_services'] = array_intersect_key($notice->services_list['all_services'], $notice->services_list['not_added_services']);
                }

                $services_ids = array(
                    'add'    => array_keys($notice->services_list['to_add_services']),
                    'remove' => array_keys($notice->services_list['to_remove_services'])
                );

                $notice_count['new'] = count($services_ids['add']);
                $notice_count['remove'] = count($services_ids['remove']);

                if (!empty($notice->services_list['to_remove_services']))
                    $notice->remove('services', $services_ids['remove']);

                if (!$notice->save_statuses)
                    $notice_count['reread'] = $notice->update_to_unread();

                if (!empty($notice->services_list['to_add_services']))
                    $notice->add('services', $services_ids['add']);

                $mail_count = $notice->send_email();

                $msg = 'Уведомление "';
                $msg .= (mb_strlen($old_values['title']) == 0) ? Text::limit_words(strip_tags($old_values['text']), 7) : $old_values['title'];
                $msg .= '" отредактировано. <br />';
                if ($notice_count['new'] > 0)
                    $msg .= 'Отправлено новым сервисам: '.$notice_count['new'].'.<br />';
                if ($notice_count['reread'] > 0)
                    $msg .= 'Статус сменен на непрочитан у сервисов: '.$notice_count['reread'].'.<br />';
                if ($notice_count['remove'] > 0)
                    $msg .= 'След. кол-во сервисов убраны из списка: '.$notice_count['remove'].'.<br />';
                if ($mail_count['new'] > 0)
                    $msg .= 'Новых email сообщений отправлено в очередь: '.$mail_count['new'].'.<br />';
                if ($mail_count['resend'] > 0)
                    $msg .= 'След. кол-во email сообщений по новой отправлено в очередь: '.$mail_count['resend'].'.<br />';
                if ($mail_count['deleted'] > 0)
                    $msg .= 'След. кол-во писем удалено из очереди на отправку '.$mail_count['deleted'].'.';

                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect('admin/notice');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $notice->as_array();
            foreach ($notice->services->find_all() as $s)
            {
                $this->values['service'][] = $s->id;
            }
        }
        $this->view = View::factory('backend/notice/form_service')
                          ->set('url', 'admin/notice/edit_service/'.$notice->id)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('services', $notice->services_list['all_services'])
                          ->set('mode', 'edit');
        $this->template->title = 'Редактирование уведомления';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Добавление уведомления для пользователя
     * @return void
     */
    public function action_add_user()
    {
		Message::set(Message::ERROR, 'Уведомления для пользователей отключено');
		$this->request->redirect('admin/notice');
        $user = ORM::factory('user');
        $this->values['user'][] = $this->request->param('id', NULL);
      
        if ($_POST)
        {
            $users_for_send = Arr::get($_POST, 'user', array());
            $mail_list = array();
            $notice_count = 0;
            $notice = ORM::factory('notice')->values($_POST, array('title', 'text'));
            $notice->type = 'user';
            $notice->for = (!empty($users_for_send) AND is_array($users_for_send)) ? 'optional' : 'all';
            $notice->date = Date::formatted_time();
            try
            {
                $notice->save();
                if (!empty($users_for_send) AND is_array($users_for_send))
                {
                    $user->where('id', 'in', $users_for_send);
                }
                foreach ($user->find_all() as $u)
                {
                    $notice->add('users', $u->id);
                    $mail_list[$u->id] = array(
                        'username' => $u->username,
                        'email'    => $u->email
                    );
                    $notice_count++ ;
                }
                unset($u);
                // Отправка писем в очередь
                $this->_send_email($notice, $mail_list);
                Message::set(Message::SUCCESS, 'Уведомления отправлено следующему кол-ву пользователей: '.$notice_count.'. След. кол-во писем отправлено в очедерь: '.count($mail_list));
                $this->request->redirect('admin/notice/user');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        $this->view = View::factory('backend/notice/form_user')
                          ->set('url', 'admin/notice/add_user')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('user', $user);
        $this->template->title = 'Добавление уведомления для пользователя';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Редактирование уведомлений для пользователей
     * @return void
     */
    public function action_edit_user()
    {
		Message::set(Message::ERROR, 'Уведомления для пользователей отключено');
		$this->request->redirect('admin/notice');
        $notice = ORM::factory('notice', $this->request->param('id', NULL));
        if (!$notice->loaded() OR $notice->type != 'user')
        {
            $this->request->redirect('admin/notice');
        }
        $user = ORM::factory('user');
        $subscribe = ORM::factory('subscribe_notice');
        $new_notice_count = 0;
        $reread_notice_count = 0;
        $mail_count = 0;
        $deleted_mail_count = 0;


        $save_status_mode = Arr::get($_POST, 'save_status', FALSE);
        //$resend_email_mode = Arr::get($_POST, 'resend_email', FALSE);
        
        $remove_user_list = array();
        $users_for_send = Arr::get($_POST, 'user', array());
        if ($_POST)
        {
            
            $old_title_text = (mb_strlen($notice->title) == 0) ? Text::limit_words(strip_tags($notice->text), 7) : $notice->title;
            $current_user_list = $notice->get_users_ids();
            $notice->values($_POST, array('title', 'text'));
            $notice->for = (!empty($users_for_send) AND is_array($users_for_send)) ? 'optional' : 'all';
            try
            {
                $notice->update();
                // Состовляем список для удаления а так же условия для выборки пользователей
                if (!empty($users_for_send) AND is_array($users_for_send))
                {
                    $remove_user_list = array_diff($current_user_list, $users_for_send);
                    $user->where('id', 'in', $users_for_send);
                }
                // Удаляем лишних
                if (!empty($remove_user_list))
                {
                    $notice->remove('users', $remove_user_list);
                    $deleted_mail_count = $subscribe->remove_queue($notice->id, array('user_id', 'in', $remove_user_list));
                }
                // Если не выбран чекбокс на сохранение статусов
                if (!$save_status_mode)
                {
                    $reread_notice_count = $notice->update_status_to_unread('user');
                    $mail_count = $subscribe->update_status_to_queue($notice->id);
                    $notice->date = Date::formatted_time();
                    $notice->update();
                }

                foreach ($user->find_all() as $u)
                {
                    if (!$notice->has('users', $u->id))
                    {
                        $notice->add('users', $u->id);
                        $new_notice_count++ ;
                        $status = $subscribe->get_status($notice->id, $u->id);
                        if (!$status)
                        {
                            if ($subscribe->new_subscribe($notice->id, $u->id))
                                $mail_count++ ;
                        }
                        elseif ($status == 'send')
                        {
                            if ($subscribe->new_subscribe($notice->id, $u->id))
                                $mail_count++ ;
                        }
                    }
                }
                unset($u);

                $msg = 'Уведомление "'.$old_title_text.'" отредактактировано.';

                if ($new_notice_count > 0)
                    $msg .= 'Уведомление отправлено след. кол-ву пользователей: '.$new_notice_count.'. ';
                if ($reread_notice_count > 0)
                    $msg .= 'След. кол-ву пользователей статус уведомления обновлен на "непрочитано": '.$reread_notice_count.'. ';
                if ($mail_count > 0)
                    $msg .= 'На очередь на отправку Email отправлено: '.$mail_count.'. ';
                if (count($remove_user_list) > 0)
                    $msg = 'След. кол-во пользователей убрано из списка: '.count($remove_user_list).'. ';
                if ($deleted_mail_count > 0)
                    $msg .= 'Из очереди на отправку убрано след. кол-во писем: '.$deleted_mail_count;

                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect('admin/notice/user');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $notice->as_array();
            foreach ($notice->users->find_all() as $u)
            {
                $this->values['user'][] = $u->id;
            }
        }

        $this->view = View::factory('backend/notice/form_user')
                          ->set('url', 'admin/notice/edit_user/'.$notice->id)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('user', $user)
                          ->set('mode', 'edit');

        $this->template->title = 'Добавление уведомления для пользователей';
        $this->template->bc['admin/notice/user'] = 'Уведомления для пользователей';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Редактирование системных уведомлений
     * @return void
     */
    public function action_edit_system()
    {
        $notice = ORM::factory('notice', $this->request->param('id', NULL));
        if (!$notice->loaded() OR $notice->type == 'service' OR $notice->type == 'user')
        {
            $this->request->redirect('admin/notice');
        }
        if ($_POST)
        {
            $this->validation = Validation::factory($_POST)
                                          ->rule('text', 'not_empty');
            if ($this->validation->check())
            {
                DB::update('notices')->set(array('text' => $this->validation['text'], 'title' => $this->validation['title']))->where('type', '=', $notice->type)->execute();
                Message::set(Message::SUCCESS, 'Системное уведомление отредактировано');
                $this->request->redirect('admin/notice/system');
            }
            else
            {
                $this->errors = $this->validation->errors('models/notice');
                $this->values = $_POST;
            }
        }
        else
        {
            $this->values = $notice->as_array();
        }
        $this->view = View::factory('backend/notice/form_system')
                          ->set('url', 'admin/notice/edit_system/'.$notice->id)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors);
        $this->template->title = 'Системные уведомления';
        $this->template->bc['admin/notice/system'] = $this->template->title;
        $this->template->bc['#'] = $notice->description;
        $this->template->content = $this->view;
    }
    function action_delete()
    {
        $notice = ORM::factory('notice', $this->request->param('id', NULL));
        if (!$notice->loaded() OR ($notice->type != 'service' AND $notice->type != 'user'))
        {
            $this->request->redirect('admin/notice');
        }
        $count = ($notice->type == 'service') ? count($notice->services->find_all()) : count($notice->users->find_all());
        $deleted_mail_count = 0;
        if ($_POST)
        {
            $action = Arr::extract($_POST, array('submit', 'cancel'));
            if ($action['cancel'])
            {
                $this->request->redirect('admin/notice');
            }
            if ($action['submit'])
            {
                $msg = 'Уведомление для '.$count;
                $msg .= ($notice->type == 'service') ? ' компаний удалено.' : ' пользователей удалено.';

                // Удаляем связи
                if ($notice->type == 'service')
                    $notice->remove('services');
                else
                    $notice->remove('users');

                foreach ($notice->subscribes->find_all() as $s)
                {
                    if ($s->email->status == 'queue')
                    {
                        $s->email->delete();
                        $deleted_mail_count++ ;
                    }
                    $s->delete();
                }
                $notice->delete();
                if ($deleted_mail_count > 0)
                    $msg .= ' След. кол-во email сообщений удалено из очереди: '.$deleted_mail_count;
                Message::set(Message::SUCCESS, $msg);
                $this->request->redirect('admin/notice');
            }
        }
        $text = 'Вы действительно хотите удалить уведомление для след. кол-ва ';
        $text .= ($notice->type == 'service') ? 'компаний' : 'пользователей';
        $text .= ' '.$count;
        $this->view = View::factory('backend/delete')
                          ->set('url', 'admin/notice/delete/'.$notice->id)
                          ->set('text', $text);
        $this->template->title = 'Удаление уведомления';
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
    /**
     * Функция отправки писем в очередь
     * @param array $mail_list
     * @return void
     */
    private function _send_email(Model_Notice $notice, Array $user_list)
    {
        $messages_list_columns = array(
            'mail_from',
            'mail_to',
            'title',
            'text',
            'date_create'
        );
        $date = Date::formatted_time();
        $email_view = View::factory('email/notice');
        $email_view->set('title', $notice->title)
                   ->set('text', $notice->text);
        foreach ($user_list as $user_id => $value)
        {
            $email_view->set('username', $value['username'])
                       ->render();

            $message_id = DB::insert('messages_list', $messages_list_columns)
                            ->values(array('no-reply@as-avtoservice.ru', $value['email'], 'Уведомление от ассоциации автосервисов', $email_view, $date))
                            ->execute();

            DB::insert('notice_subscribe', array('user_id', 'notice_id', 'email_id'))->values(array($user_id, $notice->id, $message_id))->execute();
        }
    }
}