<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Registration extends Controller_Frontend
{

    function action_index()
    {


	    if ($this->auth->logged_in())
        {
            Message::set(Message::ERROR, 'Вы уже итак зашли');
            $this->request->redirect('/');
        }

	    $test_period = DB::select('days')
	    			        ->from('payment_settings')
	    			        ->where('status', '=', 'Y')
	    	                ->where('system_name', '=', 'test_period')
	    			        ->execute()->get("days");

		if ($_POST)
        {
            $post = Arr::map('trim', $_POST);
            $this->validation = Validation::factory($_POST)
                                          ->rule('password', 'not_empty', array(':value'))
                                          ->rule('password', 'min_length', array(':value', 6))
                                          ->rule('password', 'alpha_dash', array(':value'))
                                          ->rule('accept_rule', 'not_empty');
            $user = ORM::factory('user');
            try
            {

				$email_confirm_code = substr(md5(uniqid()),0,10);

                $user->values($post, array('username', 'password', 'email'));

                $user->values(array("user_type"=>"unconfirmed","email_confirm_code"=>$email_confirm_code)); // yaap - save user as unconfirmed
	            $user->expires = date('Y-m-d H:i:s',time()+intval($test_period)*24*60*60);
                $user->save($this->validation);
                // Add role for login
                $user->add('roles', 1);

                $notice = ORM::factory('notice')->where('type', '=', 'registration_complete')->find();
                DB::insert('notices_users', array('user_id', 'notice_id', 'date'))->values(array($user->id, $notice->id, Date::formatted_time()))->execute();

                $email_view = View::factory('email/registration_complete')
                                  ->set('text', $notice->text)
                                  ->set('username', $user->username)
                                  ->set('email_confirm_link', "http://as-avtoservice.ru/registration/confirm?email=".$user->email."&code=".$email_confirm_code)
                                  ->render();
                $message = ORM::factory('emailsender');
                $message->mail_to = $user->email;
                $message->mail_from = 'no-reply@as-avtoservice.ru';
                $message->title = 'Регистрация на сайте';
                $message->text = $email_view;
                $message->status = 'queue';
                $message->date_create = Date::formatted_time();
                $message->save();

                Message::set(Message::SUCCESS, __('s_reg_success_2'));
                $this->request->redirect('registration/confirm');

            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');

                //echo $this->errors['accept_rule'];
                $this->values = $post;
            }
        }
        $reg_step_view = View::factory('frontend/auth/register/steps')
                             ->set('step', 1);
        $this->view = View::factory('frontend/auth/register/first')
                          ->set('errors', $this->errors)
                          ->set('values', $this->values)
                          ->set('reg_steps', $reg_step_view);
        $this->template->title = $this->site_name.__('a_registration');
        $this->template->bc['#'] = 'Регистрация';
        $this->template->content .= $this->view;

    }

    function action_second()
    {
        $reg_status = $this->session->get('reg_status', NULL);
        if (empty($reg_status) AND $reg_status['step'] != 2)
        {
            $this->request->redirect('/');
        }
        
        $this->_company_settings = Kohana::$config->load('settings');
        $city = ORM::factory('city');
        $cities = $city->get_all_cities();
        $org_types = ORM::factory('orgtype')->get_all_as_array();
        if ($_POST)
        {
            $post = Arr::map('trim', $_POST);
            if (isset($post['skip']))
            {
                Message::set(Message::SUCCESS, 'Вы успешно зарегистрировались, но не добавили компанию. Вы можете сделать это в '.HTML::anchor('cabinet', 'личном кабинете').'.');
                $this->session->delete('reg_status');
                $this->request->redirect('cabinet');
            }
            $service = ORM::factory('service');
            try
            {
                $service->values($post, array('type', 'name', 'org_type', 'inn', 'director_name', 'contact_person', 'address', 'code', 'phone', 'fax', 'site', 'ymap_lng', 'ymap_lat'));
                $service->discount_id = 0;
                $service->user_id = $this->user->id;
                $service->city_id = Arr::get($_POST, 'city_id', NULL);
                $service->metro_id = Arr::get($post, 'metro_id', NULL);
                $service->district_id = Arr::get($post, 'district_id', NULL);
                $date = Date::formatted_time();
                $service->date_create = $date;
                $service->date_edited = $date;
                $service->save();
                $reg_status = array(
                    'step'    => 3,
                    'service_id' => $service->id
                );
                if ($service->type == 1)
                {
                    $notice_type = 'service_create';
                }
                else
                {
                    $notice_type = 'shop_create';
                }

                $notice = ORM::factory('notice')->where('type', '=', $notice_type)->find();
                DB::insert('notices_services', array('service_id', 'notice_id', 'date'))->values(array($service->id, $notice->id, Date::formatted_time()))->execute();

                $email_view = View::factory('email/notice')
                                  ->set('text', $notice->text)
                                  ->set('title', $notice->title)
                                  ->set('username', $this->user->username)
                                  ->render();
                $message = ORM::factory('emailsender');
                $message->mail_to = $this->user->email;
                $message->mail_from = 'no-reply@as-avtoservice.ru';
                $message->title = 'Уведомление от ассоциации автосервисов';
                $message->text = $email_view;
                $message->status = 'queue';
                $message->date_create = Date::formatted_time();
                $message->save();

                $this->session->set('reg_status', $reg_status);
                Message::set(Message::NOTICE, __('s_reg_success_3'));
                $this->request->redirect('registration/third');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->values = $post;
                $this->errors = $e->errors('models');
            }
        }

        $reg_step_view = View::factory('frontend/auth/register/steps')
                             ->set('step', 2);
        $this->view = View::factory('frontend/auth/register/second')
                          ->set('company_types', $this->_company_settings['company_types'])
                          ->set('org_types', $org_types)
                          ->set('city', $city)
                          ->set('cities', $cities)
                          ->set('test', $reg_status)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('reg_steps', $reg_step_view);
        $this->add_js('http://api-maps.yandex.ru/1.1/index.xml?key='.$this->settings['YMaps_key'].'&onerror=map_alert');
        $this->template->title = $this->site_name.__('s_reg_2_step');
        $this->template->bc['#'] = 'Регистрация';
        $this->template->content .= $this->view;
    }

    function action_third()
    {
        $reg_status = $this->session->get('reg_status', NULL);
        if (empty($reg_status) AND $reg_status['step'] != 3 AND !isset($reg_status['service_id']))
        {
            $this->request->redirect('/');
        }

        $service = ORM::factory('service', $reg_status['service_id']);
        if ($service->user->id != $this->user->id OR !$service->loaded())
        {
            $this->request->redirect('/');
        }

        $work_category = ORM::factory('workcategory');
        $cars = ORM::factory('car_brand')->get_cars_as_array();
        $discounts = ORM::factory('discount')->get_all_as_array();

        if ($_POST)
        {
            $post = Arr::map('trim', $_POST);
            if (isset($post['skip']))
            {
                Message::set(Message::SUCCESS, 'Вы успешно зарегистрировались, но не указали дополнительную информацию для фирмы. Вы можете сделать это в '.HTML::anchor('cabinet', 'личном кабинете').'.');
                $this->session->delete('reg_status');
                $this->request->redirect('cabinet');
            }            

            try
            {
                $service->values($post, array('about', 'work_times', 'discount_id', 'coupon_text'));

                $cars_input = Arr::get($_POST, 'model', array());
                foreach ($cars_input as $c)
                {
                    $service->add('cars', $c);
                }
                
                if ($service->type == 1)
                {
                    $works_input = Arr::get($_POST, 'work', array());
                    foreach ($works_input as $w)
                    {
                        $service->add('works', $w);
                    }
                }
                $service->update();
                Message::set(Message::SUCCESS, 'Вы успешно зарегистрировались.');
                $this->session->delete('reg_status');
                $this->request->redirect('cabinet');
                //throw ORM_Validation_Exception::text('')
            }
            catch(ORM_Validation_Exception $e)
            {
                $this->values = $post;
                $this->errors = $e->errors('models');
            }
        }
        $reg_step_view = View::factory('frontend/auth/register/steps')
                             ->set('step', 3);
        $this->view = View::factory('frontend/auth/register/third')
                          ->set('type', $service->type)
                          ->set('discounts', $discounts)
                          ->set('work_category', $work_category)
                          ->set('auto_models', $cars)
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('reg_steps', $reg_step_view);
        $this->template->title = $this->site_name.__('s_reg_3_step');
        $this->template->bc['#'] = 'Регистрация';
        $this->template->content .= $this->view;

    }

	function action_confirm() {
		$email = Arr::get($_GET, 'email', "");
		$code = Arr::get($_GET, 'code', "");


		if(empty($email) || empty($code)) {
			$message = __('s_reg_confirm_info');
		}else {
			$user = ORM::factory('user')->where('email', '=', $email)->find();
			if(!$user->id){
				$message = __('s_reg_confirm_user_nor_found');
			}elseif ($user->user_type != "unconfirmed" || empty($user->email_confirm_code)) {
				$message = __('s_reg_confirm_not_need');
			}elseif ($user->email_confirm_code == $code) {

				$message = __('s_reg_confirm_success');

				$user->values(array("user_type"=>"service","email_confirm_code"=>NULL));
				$user->save($this->validation);

				$this->auth->force_login($user->username);
	            $reg_status['step'] = 2;
	            $this->session->set('reg_status', $reg_status);
	            Message::set(Message::SUCCESS, __('s_reg_success_confirm'));
	            $this->request->redirect('registration/second');

			}else {
				$message = __('s_reg_confirm_error');
			}
		}


		$this->view = View::factory('frontend/auth/register/confirm')
		                          ->set('message', $message);

        $this->template->title = $this->site_name.__('s_reg_confirm');
        $this->template->bc['#'] = 'Регистрация';
        $this->template->content .= $this->view;

	}
}