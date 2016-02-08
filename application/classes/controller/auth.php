<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Auth extends Controller_Frontend {

	private $_step;

	function action_index() {

		$this->request->redirect('/');
	}

	/**
	 * Отправка письма на восстановление пароля
	 * @return
	 */
	public function action_forgot_password() {

		$this->template->title = $this->site_name . 'Восстановление пароля';
		$this->template->bc['#'] = 'Восстановление пароля';
		if ($_POST) {
			$validation = Validation::factory($_POST)
					->rule('username_email', 'not_empty');
			if ($validation->check()) {
				$have_user = DB::select('email', 'id', 'username')->from('users')->where('username', '=', $validation['username_email'])->or_where('email', '=', $validation['username_email'])->execute()->current();
				if ($have_user) {
					//echo $have_user;
					$key = md5($validation['username_email']);

					DB::insert('recover_passwords', array('key', 'user_id'))->values(array($key, $have_user['id']))->execute();
					$email_view = View::factory('email/recover_password')
							->set('username', $have_user['username'])
							->set('key', $key)
							->render();
					Email::send($have_user['email'], array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), 'Восстановление пароля', $email_view, true);
					$view = View::factory('frontend/auth/forgot_send_email_complete');
					$this->template->content = $view;

					return;
				} else {
					$this->errors['username_email'] = 'Такой пользователь не найден';
				}
			} else {
				$this->errors = $validation->errors('registration');
			}

		}
		$this->view = View::factory('frontend/auth/forgot')
				->set('values', $this->values)
				->set('errors', $this->errors);
		$this->template->content = $this->view;
	}

	/**
	 * Ввод нового пароля
	 * @return
	 */
	function action_activate_password() {

		$this->template->title = $this->site_name . 'Восстановление пароля';
		$this->template->bc['#'] = 'Восстановление пароля';

		$key = Arr::get($_GET, 'key', null);
		if ($key) {
			$activate_pass = DB::select('user_id')->from('recover_passwords')->where('key', '=', $key)->execute()->get('user_id');
			if ($activate_pass) {
				if ($_POST) {
					try {
						$user = ORM::factory('user', $activate_pass);
						$user->password = $_POST['password'];
						$user->update();
						DB::delete('recover_passwords')->where('key', '=', $key)->execute();
						$this->values['username'] = $user->username;

						$this->template->title = $this->site_name . 'Авторизация';
						$this->template->bc['#'] = 'Авторизация';
						Message::set(Message::SUCCESS, 'Теперь вы можете войти');

						$view = View::factory('frontend/auth/login')
								->set('values', $this->values)
								->set('errors', $this->errors);
						$this->template->content = $view;

						return;
					} catch (ORM_Validation_Exception $e) {
						$this->errors = $e->errors('models');
					}
				}


				$view = View::factory('frontend/auth/activate_new_password')
						->set('key', $key)
						->set('errors', $this->errors);
				$this->template->content = $view;
			} else {
				$this->errors['username_email'] = 'Ошибка восстановления, попробуйте еще раз';
				$view = View::factory('frontend/auth/forgot')
						->set('values', $this->values)
						->set('errors', $this->errors);
				$this->template->content = $view;

				return;
			}
		} else {
			$this->request->redirect('/');
		}
		//$this->template->content = $key;
	}

	function action_login() {

		$this->view = View::factory('frontend/auth/login')
				->bind('values', $this->values)
				->bind('errors', $this->errors);
		if ($_POST) {
			$post = Arr::map('trim', $_POST);

			if ($this->auth->login($post['username'], $post['password'])) {

				$user = $this->auth->get_user();
				// проверяем настройки доступа человека - не просрочен ли его аккаунт
				if ($user->user_type == 'unconfirmed') {
					$this->auth->logout();
					Message::set(Message::NOTICE, 'Вы должны активировать ваш аккаунт');
					$this->request->redirect('/auth/confirm_email');
				}

				Message::set(Message::SUCCESS, 'Добро пожаловать!');
				Logger::write(Logger::ACTION, 'Пользователь авторизовался', $user);
				$this->request->redirect('cabinet');
			} else {
				Message::set(Message::ERROR, 'Неправильное имя пользователя или пароль');
			}
		}
		$this->template->content = $this->view;

	}

	function action_logout() {

		if ($this->auth->logged_in()) {
			$this->auth->logout();
			$this->request->redirect('/');
		} else {
			$this->add_debug('Ты еще не вошел чтобы выйти');
		}
	}


	/**
	 * Ввод нового пароля
	 * @return
	 */
	function action_confirm_email() {

		$this->template->title = $this->site_name . 'Инструкции по активакии аккаунта';
		$this->template->bc['#'] = 'Активация аккаунта';


		if ($_POST) {
			$email = Arr::get($_POST, 'email', "");
			$user = ORM::factory('user')->where('email', '=', $email)
				->limit(1)
				->find();

			if($user->loaded()){
				try {
						$notice = ORM::factory('notice')->where('type', '=', 'registration_complete')->find();
						DB::insert('notices_users', array('user_id', 'notice_id', 'date'))->values(array($user->id, $notice->id, Date::formatted_time()))->execute();

						$email_view = View::factory('email/registration_complete')
								->set('text', $notice->text)
								->set('username', $user->username)
								->set('email_confirm_link', "http://as-avtoservice.ru/registration/confirm?email=" . $user->email . "&code=" . $user->email_confirm_code)
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


					} catch (ORM_Validation_Exception $e) {
						$this->errors = $e->errors('models');
						$this->values = $_POST;
					}
			} else {
				Message::set(Message::ERROR, "Пользователь с такими данными не найден") ;
				$this->values = $_POST;
			}

		}


		$view = View::factory('frontend/auth/confirm_email')
				->set('errors', $this->errors)
				->set('values', $this->values);
		$this->template->content = $view;

	}

}