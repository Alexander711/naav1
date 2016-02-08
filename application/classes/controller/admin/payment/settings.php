<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Payment_Settings extends Controller_Backend {

	public function before() {

		parent::before();
		$this->template->bc['admin/payment/settings'] = 'Настройки платежей';
	}

	function action_index() {

		$settings = ORM::factory('payment_settings');

		$this->view = View::factory('backend/payment/settings/all')
				->set('settings', $settings);

		$this->template->title = 'Настройки платежей';
		$this->template->content = $this->view;
	}


	/**
	 * Редактирование новости автосервиса
	 * @return void
	 */
	function action_edit() {

		$id = $this->request->param('id', null);
		if (!empty($id)) {
			$settings = ORM::factory('payment_settings', $id);
			if (!$settings->loaded()) {
				Message::set(Message::ERROR, Kohana::message('admin', 'payment.settings_not_found'));
				$this->request->redirect('admin/payment/settings');
			}
			$this->values = $settings->as_array();
		} else {
			$settings = ORM::factory('payment_settings');
		}


		if ($_POST) {

			try {

				$settings->values($_POST, array('name', 'price', 'days', 'in_list', 'status', 'sort','value'));
				$settings->save();

				Message::set(Message::SUCCESS, 'Настройка "' . $settings->name . '" сохранена');
				$this->request->redirect('admin/payment/settings');

			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
				$this->values = $_POST;
			}
		}


		$this->view = View::factory('backend/payment/settings/form')
				->set('errors', $this->errors)
				->set('values', $this->values)
				->set('url', 'admin/payment/settings/edit/' . $id);


		$this->template->title = empty($id) ? 'Новая настройка' : 'Редактирование настройки  "' . $settings->name;
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;


	}

	/**
	 * Удаление новости автосервиса
	 * @return void
	 */
	function action_delete() {

		$settings = ORM::factory('payment_settings', $this->request->param('id', null));
		if (!$settings->loaded()) {
			Message::set(Message::ERROR, Kohana::message('admin', 'payment.settings_not_found'));
			$this->request->redirect('admin/payment/settings');
		}

		if ($settings->system == 'Y') {
			Message::set(Message::NOTICE, 'Нельзя удалять системные настройки');
			$this->request->redirect('admin/payment/settings');
		}

		if ($_POST) {

			$action = Arr::extract($_POST, array('submit', 'cancel'));
			if ($action['cancel']) {
				$this->request->redirect('admin/payment/settings');
			}
			if ($action['submit']) {
				$name = $settings->name;


				$settings->delete();
				Message::set(Message::SUCCESS, 'Платежная настройка <strong>' . $name . '</strong> удалена');
				$this->request->redirect('admin/payment/settings');
			}
		}


		$this->view = View::factory('backend/delete')
				->set('url', 'admin/payment/settings/delete/' . $settings->id)
				->set('from_url', 'admin/payment/settings')
				->set('title', 'Удаление платежной настройки: ' . $settings->name)
				->set('text', 'Вы действительно хотите удалить "' . $settings->name . '?');
		$this->template->title = 'Удаление новости "' . $settings->name . '"';
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;
	}


}