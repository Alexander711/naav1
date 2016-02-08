<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Payment extends Controller_Cabinet {

	function before() {

		$this->allow_expired = true;

		parent::before();

		$this->template->bc['cabinet/payment'] = 'Пополнение баланса';
	}

	function action_index() {
		$this->check_entrance_fee();

		$invoices = $this->user->invoices;
		$this->view = View::factory('frontend/cabinet/payment/all')
				->set('invoices', $invoices);


		$this->template->title = 'Список платежей';
		$this->template->content = $this->view;
	}




	function action_show() {
		$id = $this->request->param('id', null);
		if(empty($id))  $this->request->redirect('cabinet/payment/');

		$this->check_entrance_fee($id);


		$invoice = $this->user->invoices->where('id','=',$id)->find();
		if (!$invoice->loaded()) {
			Message::set(Message::ERROR, 'Платеж не найден');
			$this->request->redirect('cabinet/payment/');
		}

		$paymentSystem = $invoice->payment;

		$paymentForm = "";
		// render payment form from PS class
		if(!empty($paymentSystem->controller) && is_callable(array($paymentSystem->controller,"render_form"))) {
			$paymentForm = call_user_func_array(array($paymentSystem->controller,"render_form"), array($invoice->id, $invoice->amount));
		}

		$this->view = View::factory('frontend/cabinet/payment/show')
				->set('invoice', $invoice)
				->set('paymentForm', $paymentForm)
				->set('paymentSystem', $paymentSystem);

		$this->template->title = 'Платеж # UPI - '.$id;
		$this->template->content = $this->view;
	}

	function action_add() {
		$this->check_entrance_fee();

		//  выбираем среди платежей тот, который  вступительный.
		//Если он есть - больше в списке не показываем. Нет - показываем и позволяем выбирать только его

		$entrance_fee = DB::select('id')
					        ->from('invoice')
					        ->where('user_id', '=',  $this->user->id) // оплаченый платеж
					        ->where('status', '=', 'P') // оплаченый платеж
			                ->where('entrance_fee', '=', 'Y') // вступ. взнос
					        ->limit(1)
					        ->execute()->as_array('id');

		$settings = DB::select('id','name','price','days','system_name')
					        ->from('payment_settings')
					        ->where('status', '=', 'Y')
			                ->where('in_list', '=', 'Y');


		if(empty($entrance_fee)){
			$settings->where("system_name","=","entrance_fee");
		} else {
			$settings->where_open()->where("system_name","!=","entrance_fee")->or_where("system_name","=",NULL)->where_close();
		}


		$settings = $settings->order_by('sort', 'ASC')->execute()->as_array('id');


	    $payments = DB::select('id','payment_name','tips','description')
			        ->from('payment')
			        ->where('status', '=', 'Y')
	                ->order_by('position', 'ASC')
			        ->execute()->as_array('id');

		if(empty($payments) || empty($settings)){
			Message::set(Message::ERROR, "Не заданы тарифы оплаты. Обратитесь к администратору сайта.");
			$this->request->redirect('cabinet/payment/');
		}


		if ($_POST) {

			$payment_value = Arr::get($_POST,"payment_value");
			$payment_system = Arr::get($_POST,"payment_system");

			$invoice = ORM::factory('invoice');



			try {

				if(empty($payment_value) || empty($payment_value)) throw new Exception('empty_value');
				if(!isset($settings[$payment_value]) || !isset($payments[$payment_system])) throw new Exception('not_exists');

				$invoice->user_id = $this->user->id;
				$invoice->payment_id = $payment_system;
				$invoice->amount = $settings[$payment_value]['price'];
				$invoice->days_amount = $settings[$payment_value]['days'];
				$invoice->create_date = Date::formatted_time();
				$invoice->modify_date = Date::formatted_time();
				$invoice->status = 'N';
				// проверяем не вступительный ли платеж
				if($settings[$payment_value]['system_name'] == "entrance_fee")
					$invoice->entrance_fee = "Y";


				$invoice->save();

				$this->request->redirect('cabinet/payment/show/'.$invoice->id);
			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
				$this->values = $_POST;
			} catch (Exception $e) {
				Message::set(Message::ERROR, 'Ошибка при обработке платежа');
				Message::set(Message::ERROR, $e->getMessage());
				$this->errors = array();
				$this->values = $_POST;
			}
		}


		$this->view = View::factory('frontend/cabinet/payment/add')
				->set('values', $this->values)
				->set('errors', $this->errors)
				->set('settings', $settings)
				->set('payments', $payments);
		$this->template->title = 'Добавить платеж';
		$this->template->bc['#'] = 'Добавить платеж';
		$this->template->content = $this->view;
	}


	function action_cancel() {
		$id = $this->request->param('id', null);
		if(empty($id))  $this->request->redirect('cabinet/payment/');

		if ($_POST) {
			if(isset($_POST['no'])) {
				$this->request->redirect('cabinet/payment/');
			}

			try {

				$invoice = $this->user->invoices->where('id','=',$id)->find();
				if (!$invoice->loaded()) {
					Message::set(Message::ERROR, 'Платеж не найден');
					$this->request->redirect('cabinet/payment/');
				}

				if($invoice->status != 'N') {
					Message::set(Message::NOTICE, 'Этот платеж нелья отменить');
				}else {
					$invoice->status = 'C';
					$invoice->save();
				}
				$invoice->status = 'C';
				$invoice->save();

				$this->request->redirect('cabinet/payment/');

			} catch (ORM_Validation_Exception $e) {
				$this->errors = $e->errors('models');
				$this->values = $_POST;
			} catch (Exception $e) {
				Message::set(Message::ERROR, 'Ошибка при обработке платежа');
				Message::set(Message::ERROR, $e->getMessage());
				$this->errors = array();
				$this->values = $_POST;
			}
		}




		$this->view = View::factory('frontend/cabinet/payment/cancel')
				->set('id', $id);

		$this->template->title = 'Отменить платеж';
		$this->template->bc['#'] = 'Отменить платеж';
		$this->template->content = $this->view;
	}


	function check_entrance_fee($invoice_id = 0){
		// проверяем нет ли неоплаченных вступительных, если есть - показываем для оплаты
		$entrance_fee = DB::select('id')
		        ->from('invoice')
		        ->where('status', '=', 'N') // оплаченый платеж
                ->where('entrance_fee', '=', 'Y') // вступ. взнос
                ->where('user_id', '=', $this->user->id)
		        ->limit(1)
		        ->execute()->get('id');


		// если есть взнос и он не совпадает с текущим (который пользоватлеь хочет оплатить)
		if(!empty($entrance_fee) && $invoice_id != $entrance_fee) {
			Message::set(Message::NOTICE, "Вы должны оплатить вступительный взнос");
			$this->request->redirect('cabinet/payment/show/'.$entrance_fee);
		}

	}




}