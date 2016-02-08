<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Payment_Main extends Controller_Backend {

	public function before() {

		parent::before();
		$this->template->bc['admin/payment/'] = 'Способы оплаты';
	}

	function action_index() {

		$payment = ORM::factory('payment');

		$this->view = View::factory('backend/payment/all')
				->set('payment', $payment);

		$this->template->title = 'Способы оплаты';
		$this->template->content = $this->view;
	}


	/**
		 * Редактирование новости автосервиса
		 * @return void
		 */
		function action_edit() {


			$id = $this->request->param('id', null);
			if (!empty($id)) {
				$payment = ORM::factory('payment', $id);
				if (!$payment->loaded()) {
					Message::set(Message::ERROR, "Платежная система не найдена");
					$this->request->redirect('admin/payment');
				}
				$this->values = $payment->as_array();
			} else {
				Message::set(Message::ERROR, "Платежная система не найдена");
				$this->request->redirect('admin/payment');
			}


			if ($_POST) {

				try {

					$payment->values($_POST, array('payment_name', 'status', 'position','tips','description'));
					$payment->save();

					Message::set(Message::SUCCESS, 'Платежная система сохранена');
					$this->request->redirect('admin/payment');

				} catch (ORM_Validation_Exception $e) {
					$this->errors = $e->errors('models');
					$this->values = $_POST;
				}
			}


			$this->view = View::factory('backend/payment/form')
					->set('errors', $this->errors)
					->set('values', $this->values)
					->set('url', 'admin/payment/main/edit/' . $id);


			$this->template->title = 'Редактирование "' . $payment->payment_name;
			$this->template->bc['#'] = $this->template->title;
			$this->template->content = $this->view;


		}
}