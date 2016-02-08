<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Users extends Controller_Backend {

	public function before() {

		parent::before();
		$this->_base_url = 'admin/users';
		$this->template->title = 'Пользователи';
		$this->template->bc[$this->_base_url] = $this->template->title;
	}

	/**
	 * Просмотр пользователей
	 * @return void
	 */
	function action_index() {

		// получаем общее количество
		$count = ORM::factory('user')->count_all();

		// передаем значение количества товаров в модуль pagination и формируем ссылки
		$pagination = Pagination::factory(
			array('total_items' => $count, 'items_per_page' => 10,)
		);

		$user = ORM::factory('user')
				->limit($pagination->items_per_page)
				->offset($pagination->offset);

		$this->view = View::factory('backend/user/all')
				->set('user', $user)
				->set('pagination', $pagination);
		$this->template->content = $this->view;

	}

	public function action_activate() {

		$url = $this->request->referrer();
		$url = empty($url) ? 'admin/users' : $url;


		$user = ORM::factory('user', $this->request->param('id', null));
		if (!$user->loaded()) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_not_found'));
			$this->request->redirect($url);
		}
		if ($user->has('roles', 1)) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_is_active'));
			$this->request->redirect($url);
		}
		$user->add('roles', 1);
		Message::set(Message::SUCCESS, 'Пользователь ' . $user->username . ' активирован');
		$this->request->redirect($url);
	}

	public function action_deactivate() {

		$url = $this->request->referrer();
		$url = empty($url) ? 'admin/users' : $url;

		$user = ORM::factory('user', $this->request->param('id', null));
		if (!$user->loaded()) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_not_found'));
			$this->request->redirect($url);
		}
		if (!$user->has('roles', 1)) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_is_unactive'));
			$this->request->redirect($url);
		}
		$user->remove('roles', 1);
		Message::set(Message::SUCCESS, 'Пользователь ' . $user->username . ' деактивирован');
		$this->request->redirect($url);
	}

	public function action_delete() {

		$user = ORM::factory('user', $this->request->param('id', null));
		if (!$user->loaded()) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_not_found'));
			$this->request->redirect('admin/users');
		}
		if ($_POST) {
			$action = Arr::extract($_POST, array('submit', 'cancel'));
			if ($action['cancel']) {
				$this->request->redirect('admin/users');
			}
			if ($action['submit']) {
				$username = $user->username;
				foreach ($user->services->find_all() as $service) {
					$service->remove('notices');
					$service->remove('cars');
					$service->remove('works');
					$service->delete();
				}
				foreach ($user->news_service->find_all() as $news) {
					$news->delete();
				}
				foreach ($user->stocks->find_all() as $stock) {
					$stock->delete();
				}
				foreach ($user->vacancies->find_all() as $vacancy) {
					$vacancy->delete();
				}
				foreach ($user->reviews->find_all() as $review) {
					$review->delete();
				}
				foreach ($user->answers->find_all() as $answer) {
					$answer->delete();
				}
				$user->remove('notices');
				$user->delete();
				Message::set(Message::SUCCESS, 'Пользователь "' . $username . '" удален');
				$this->request->redirect('admin/users');
			}
		}

		$this->view = View::factory('backend/delete')
				->set('url', 'admin/users/delete/' . $user->id)
				->set('text', 'Вы действительно хотите удалить пользователя ' . $user->username . ' (будут удалены все компании, новости, акции, вакансии, ответы на запросы, отзывы данного пользователя)?');
		$this->template->title = 'Удаление пользователя "' . $user->username . '"';
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;


	}

	private function edit() {

		$user = ORM::factory('user', $this->request->param('id', null));
		if (!$user->loaded()) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_not_found'));
			$this->request->redirect('admin/users');
		}

		return $user;
	}

	public function action_edit_info() {

		$url = $this->request->referrer();
		$url = empty($url) ? 'admin/users' : $url;

		$admin_role = ORM::factory('role')->where('name', '=', 'admin')->find()->id;

		$user = $this->edit();
		$this->values = $user->as_array();
		if ($user->has('roles', $admin_role)) {
			$this->values['is_admin'] = 1;
		}

		if ($_POST) {

			$username = $user->username;
			$user->values($_POST, array('username', 'email', 'expires','user_type'));
			try {
				$user->update();

				$is_admin = Arr::get($_POST, 'is_admin', false);

				if ($is_admin && !$user->has('roles', $admin_role)) {
					$user->add('roles', $admin_role);
				} elseif(!$is_admin) {
					$user->remove('roles', $admin_role);
				}

				Message::set(Message::SUCCESS, 'Параметры пользователя ' . $username . ' изменены');
				$this->request->redirect($url);
			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
				$this->values = $_POST;
			}
		}
		$this->template->title = 'Изменение информации';
		$this->view = View::factory('backend/user/edit/info')
				->set('values', $this->values)
				->set('title', $this->template->title)
				->set('errors', $this->errors)
				->set('user_id', $user->id);

		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;
	}

	public function action_edit_password() {

		$url = $this->request->referrer();
		$url = empty($url) ? 'admin/users' : $url;

		$user = $this->edit();
		if ($_POST) {
			$this->validation = Validation::factory($_POST)
					->rule('password', 'not_empty', array(':value'))
					->rule('password', 'min_length', array(':value', 6))
					->rule('password', 'alpha_dash', array(':value'));
			try {
				$user->password = $this->validation['password'];

				$user->update($this->validation);
				Message::set(Message::SUCCESS, 'Пароль пользователя ' . $user->username . ' изменены');
				$this->request->redirect($url);
			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
			}
		}
		$this->template->title = 'Изменение пароля';
		$this->view = View::factory('backend/user/edit/password')
				->set('errors', $this->errors)
				->set('title', $this->template->title)
				->set('user_id', $user->id);
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;

	}

	public function action_edit() {

		$user = $this->get_orm_model('user', $this->_base_url);
		$edit_mode = Arr::get($_GET, 'edit', 'info');
		if ($edit_mode != 'info' AND $edit_mode != 'password')
			$edit_mode = 'info';

		$this->values = $user->as_array();

		switch ($edit_mode) {
			case 'info':
				$this->view = View::factory('backend/user/edit/info');
				$this->template->title = 'Редактировиние данных пользователя';
				$this->template->bc['#'] = $this->template->title;
				break;
			case 'password':
				$this->view = View::factory('backend/user/edit/password');
				$this->template->title = 'Изменение пароля пользователя';
				$this->template->bc['#'] = $this->template->title;
				break;
		}
		$this->view->bind('values', $this->values)
				->bind('errors', $this->errors)
				->set('title', $this->template->title);

		$username = $user->username;
		if ($this->request->method() == HTTP_Request::POST) {
			$user->values($_POST, array('username', 'email', 'password'));
			try {
				$user->update();
				$this->request->redirect($this->_base_url);
			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
				$this->values = $_POST;
			}
		}
		$this->template->content = $this->view;
	}

	public function action_add() {

		$this->view = View::factory('backend/user/add');
		$this->template->title = 'Добавить пользователя';
		$this->template->bc['#'] = $this->template->title;


		$this->view->bind('values', $this->values)
				->bind('errors', $this->errors)
				->set('title', $this->template->title);


		if ($this->request->method() == HTTP_Request::POST) {

			$user = ORM::factory('user');
			try {
				$user->values($_POST, array('username', 'email', 'password', 'expires', 'user_type'));
				$user->user_type = 'user';
				$user->confirm = 'y';
				$user->save();

				$admin_role = ORM::factory('role')->where('name', '=', 'admin')->find();

				$is_admin = Arr::get($_POST, 'is_admin', false);
				if ($is_admin) {
					$user->add('roles', $admin_role);
				} elseif (!$is_admin) {
					$user->remove('roles', $admin_role);
				}


				Message::set(Message::SUCCESS, 'Пользователь "' . $user->username . '" добавлен');
				$this->request->redirect('admin/users');
			} catch (ORM_Validation_Exception $e) {
				$this->values = $_POST;
				$this->errors = $e->errors('models');
			}

		}
		$this->template->content = $this->view;
	}

	public function action_services() {

		$user = ORM::factory('user', $this->request->param('id', null));
		if (!$user->loaded()) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_not_found'));
			$this->request->redirect('admin/users');
		}

		if (count($user->services->find_all()) == 0) {
			Message::set(Message::ERROR, Kohana::message('admin', 'user_havent_services'));
			$this->request->redirect('admin/users');
		}
		$this->view = View::factory('backend/service/services_table')
				->set('service', $user->services);
		$this->template->title = 'Просмотр компаний пользователя ' . $user->username;
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;
	}
}