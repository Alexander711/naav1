<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Payment_Invoice extends Controller_Backend {

	public function before() {

		parent::before();
		$this->template->bc['admin/payment/invoice'] = 'Платежи';
	}

	function action_index() {

		$invoice = ORM::factory('invoice');

		$this->view = View::factory('backend/payment/invoice/all')
				->set('invoice', $invoice);

		$this->template->title = 'Платежи';
		$this->template->content = $this->view;
	}


	/**
	 * Редактирование новости автосервиса
	 * @return void
	 */
	function action_edit() {


		$id = $this->request->param('id', null);
		if (!empty($id)) {
			$invoice = ORM::factory('invoice', $id);
			if (!$invoice->loaded()) {
				Message::set(Message::ERROR, "Платеж не найден");
				$this->request->redirect('admin/payment/invoice');
			}
			$this->values = $invoice->as_array();
			$this->values['username'] = $invoice->user->username;
			$this->values['payment_name'] = $invoice->payment->payment_name;
		} else {
			Message::set(Message::ERROR, "Платеж не найден");
			$this->request->redirect('admin/payment/invoice');
		}


		if ($_POST) {

			try {

				$invoice->values($_POST, array('amount', 'status', 'days_amount','entrance_fee'));
				$invoice->save();

				Message::set(Message::SUCCESS, 'Платеж сохранен');
				$this->request->redirect('admin/payment/invoice');

			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
				$this->values = $_POST;
			}
		}


		$this->view = View::factory('backend/payment/invoice/form')
				->set('errors', $this->errors)
				->set('values', $this->values)
				->set('url', 'admin/payment/invoice/edit/' . $id);


		$this->template->title = 'Редактирование платежа # UPI - "' . $invoice->id;
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;


	}


	function action_process() {


		$id = $this->request->param('id', null);
		if (empty($id)) {
			Message::set(Message::ERROR, "Платеж не найден");
			$this->request->redirect('admin/payment/invoice');
		}

		$invoice = ORM::factory('invoice', $id);
		if (!$invoice->loaded()) {
			Message::set(Message::ERROR, "Платеж не найден");
			$this->request->redirect('admin/payment/invoice');
		}


		if($invoice->status != 'N') {
			Message::set(Message::ERROR, "Невозможно провести платеж");
			$this->request->redirect('admin/payment/invoice');
		}


		try {

			$user = ORM::factory('user',$invoice->user_id);
			if(!$user->loaded()) {
				Message::set(Message::ERROR, "Пользователь платежа не найден(".$invoice->user_id.")");
				$this->request->redirect('admin/payment/invoice');
			}

			// если у пользователя просрочен аккаунт - в качестве начальной даты ставим текущую.
			// в противном случае - дату макс. срока (если захотели докупить)!!
			$old_value = $user->expires;

			if (empty($user->expires) || Date::diff($user->expires) <= 0)
				$user_date = new DateTime();
			else
				$user_date = new DateTime($user->expires);

			$user_date->modify("+ ".intval($invoice->days_amount)." days");
			$user->expires = $user_date->format("Y-m-d 23:59:59");
			$user->user_type = 'service';
			$invoice->status = 'P'; // оплачен
			$invoice->modify_date  = Date::formatted_time('now',"Y-m-d H:i:s");

			$invoice->update();
			$user->update();

			DB::insert('payment_log', array('user_id', 'type', 'old_value', 'new_value', 'cdate'))->values(array($invoice->user_id, "-", $old_value, $user->expires , Date::formatted_time()))->execute();

		} catch (Exception $e) {
			Message::set(Message::ERROR, "Произошла ошибка при проведении платежа");
			$this->request->redirect('admin/payment/invoice');
		}

		DB::insert('invoice_log', array('invoice_id', 'status', 'content', 'cdate'))->values(array($id, 'success', 'Платеж проведен администратором. См. логи начислений', Date::formatted_time()))->execute();
		Message::set(Message::SUCCESS, "Платеж проведен");
		$this->request->redirect('admin/payment/invoice');


	}
}