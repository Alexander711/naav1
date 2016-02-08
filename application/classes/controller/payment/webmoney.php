<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Payment_Webmoney extends Controller_Payment {

	var $webmoney_key = "";
	function before(){
		parent::before();
		$this->webmoney_key = DB::select('value')
						        ->from('payment_settings')
						        ->where('status', '=', 'Y')
				                ->where('system_name', '=', 'webmoney_key') // вступ. взнос
						        ->limit(1)
						        ->execute()->get('value');

	}

	function action_index() {

	}

	function action_failure() {

		$_POST['LMI_PAYMENT_NO'] = Arr::get($_POST, 'LMI_PAYMENT_NO', 0);
		$_POST['LMI_PAYMENT_AMOUNT'] = Arr::get($_POST, 'LMI_PAYMENT_AMOUNT', 0);
		$_POST['LMI_CURRENCY'] = Arr::get($_POST, 'LMI_CURRENCY', "");


		$this->view = View::factory('payment/webmoney/failure')
				->set('values', $_POST);

		$this->template->title = 'Платеж # UPI - ' . $_POST['LMI_PAYMENT_NO'];
		$this->template->content = $this->view;
	}

	function action_success() {

		$_POST['LMI_PAYMENT_NO'] = Arr::get($_POST, 'LMI_PAYMENT_NO', 0);
		$_POST['LMI_PAYMENT_AMOUNT'] = Arr::get($_POST, 'LMI_PAYMENT_AMOUNT', 0);
		$_POST['LMI_CURRENCY'] = Arr::get($_POST, 'LMI_CURRENCY', "");


		$this->view = View::factory('payment/webmoney/success')
				->set('values', $_POST);

		$this->template->title = 'Платеж # UPI - ' . $_POST['LMI_PAYMENT_NO'];
		$this->template->content = $this->view;
	}

	function action_notification() {

		$LMI_MERCHANT_ID = Arr::get($_POST, 'LMI_MERCHANT_ID', "");
		$LMI_PAYMENT_NO = Arr::get($_POST, 'LMI_PAYMENT_NO', "");
		$LMI_SYS_PAYMENT_ID = Arr::get($_POST, 'LMI_SYS_PAYMENT_ID', "");
		$LMI_SYS_PAYMENT_DATE = Arr::get($_POST, 'LMI_SYS_PAYMENT_DATE', "");
		$LMI_PAYMENT_AMOUNT = Arr::get($_POST, 'LMI_PAYMENT_AMOUNT', "");
		$LMI_CURRENCY = Arr::get($_POST, 'LMI_CURRENCY', "");
		$LMI_PAID_AMOUNT = Arr::get($_POST, 'LMI_PAID_AMOUNT', "");
		$LMI_PAID_CURRENCY = Arr::get($_POST, 'LMI_PAID_CURRENCY', "");
		$LMI_PAYMENT_SYSTEM = Arr::get($_POST, 'LMI_PAYMENT_SYSTEM', "");
		$LMI_SIM_MODE = Arr::get($_POST, 'LMI_SIM_MODE', "");
		$LMI_HASH = Arr::get($_POST, 'LMI_HASH', "");

		$hash = $LMI_MERCHANT_ID . ";" . $LMI_PAYMENT_NO . ";" . $LMI_SYS_PAYMENT_ID . ";" . $LMI_SYS_PAYMENT_DATE . ";" . $LMI_PAYMENT_AMOUNT . ";" . $LMI_CURRENCY . ";" . $LMI_PAID_AMOUNT . ";" . $LMI_PAID_CURRENCY . ";" . $LMI_PAYMENT_SYSTEM . ";" . $LMI_SIM_MODE;
		$hash .= ";".$this->webmoney_key;
		$hash = base64_encode(md5($hash, true));



		$content = "Order:$LMI_PAYMENT_NO\nAmount:$LMI_PAYMENT_AMOUNT\nCurrency:$LMI_CURRENCY\n\n";
		$status = "success";

//		$content.="\n\nSECRED KEY: ".$this->webmoney_key."\n\n";

//		$content.=$LMI_MERCHANT_ID . ";" . $LMI_PAYMENT_NO . ";" . $LMI_SYS_PAYMENT_ID . ";" . $LMI_SYS_PAYMENT_DATE . ";" . $LMI_PAYMENT_AMOUNT . ";" . $LMI_CURRENCY . ";" . $LMI_PAID_AMOUNT . ";" . $LMI_PAID_CURRENCY . ";" . $LMI_PAYMENT_SYSTEM . ";" . $LMI_SIM_MODE;



		try {
			//  проверяем хеш
			if ($LMI_HASH != $hash)  throw new Exception("Hash error ($LMI_HASH != $hash)");

			$invoice = ORM::factory('invoice',$LMI_PAYMENT_NO);
			if(!$invoice->loaded()) throw new Exception("Cannot find invoice (".$LMI_PAYMENT_NO.")");


			// проверяем фактические параметры
			if ( floatval($invoice->amount) != floatval($LMI_PAYMENT_AMOUNT))
				throw new Exception("Payment Data error ($LMI_PAYMENT_AMOUNT != $invoice->amount)");


			// проверяем статус платежа - чтоб не проплачивать уже проведенные платежи
			if ($invoice->status != 'N') throw new Exception("Invoice already processed (".$invoice->status.")");

			$user = ORM::factory('user',$invoice->user_id);
			if(!$user->loaded()) throw new Exception("Cannot find user (".$invoice->user_id.")");

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
			$status = 'error';
			$content .= $e->getMessage();
		}

		DB::insert('invoice_log', array('invoice_id', 'status', 'content', 'cdate'))->values(array($LMI_PAYMENT_NO, $status, $content, Date::formatted_time()))->execute();
		exit;

	}


	public static function render_form($invoice_id, $amount, $currency = "RUB") {

		$LMI_MERCHANT_ID = "bb2bdb8d-dcc0-41ce-b543-c5044f01db2f";
		$LMI_PAYMENT_AMOUNT = sprintf("%.2f", $amount);
		$LMI_CURRENCY = $currency;
		$LMI_PAYMENT_NO = $invoice_id;
		$LMI_PAYMENT_DESC = "Prepaid acount access (invoice: UPI - {$invoice_id})";
		$LMI_SIM_MODE = 0;


		$paymentForm = "<form action=\"https://paymaster.ru/Payment/Init\" method=\"POST\" />\n" .
				"<input type=\"hidden\" name=\"LMI_MERCHANT_ID\" value=\"" . $LMI_MERCHANT_ID . "\" />\n" .
				"<input type=\"hidden\" name=\"LMI_PAYMENT_AMOUNT\" value=\"" . $LMI_PAYMENT_AMOUNT . "\" />\n" .
				"<input type=\"hidden\" name=\"LMI_CURRENCY\" value=\"" . $LMI_CURRENCY . "\" />\n" .
				"<input type=\"hidden\" name=\"LMI_PAYMENT_NO\" value=\"" . $LMI_PAYMENT_NO . "\" />\n" .
				"<input type=\"hidden\" name=\"LMI_PAYMENT_DESC\" value=\"" . $LMI_PAYMENT_DESC . "\" />\n" .
				"<input type=\"hidden\" name=\"LMI_SIM_MODE\" value=\"" . $LMI_SIM_MODE . "\" />\n" .
				"<input type=\"submit\" value=\"Оплатить в системе Webmoney\" class=\"btn btn-success\"/>\n" .
				"</form>\n";

		return $paymentForm;
	}
}