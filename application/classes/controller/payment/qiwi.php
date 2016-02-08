<?php
defined('SYSPATH') or die('No direct script access.');


class Controller_Payment_Qiwi extends Controller_Payment {


	function before(){
		parent::before();
		$this->qiwiConfig = array(
		'shopID' => '',
		'password' => '',
		'lifetime' => 24,
		'txn-prefix' => '',
		'encrypt' => TRUE,
		'url' => 'https://ishop.qiwi.ru/xml',
		'create-agt' => 0,
		'alarm-sms' => '0',
		'alarm-call' => '0',
		'log' => FALSE
		);

		$result =  DB::select('system_name','value')
				        ->from('payment_settings')
				        ->where('status', '=', 'Y')
		                ->where('system_name', 'IN', array('qiwi_id','qiwi_password','qiwi_expires')) // вступ. взнос
				        ->execute()->as_array();

		foreach($result as $r) {
			switch ($r['system_name']) {
				case "qiwi_id":
					$this->qiwiConfig['shopID'] = $r['value'];
					break;
				case "qiwi_password":
					$this->qiwiConfig['password'] = $r['value'];
					break;
				case "qiwi_expires":
					$this->qiwiConfig['lifetime'] = intval($r['value']);
					break;

			}

		}
		require_once "qiwi/qiwi.class.php";

	}

	function action_index() {

		/*$id = @$_GET['id'];
		if(empty($id)) $id = 1;

		$this->view = View::factory('payment/qiwi/success');
		$this->template->title = 'Платеж # UPI - ' . $id;
		$this->template->content = $this->render_form($id,0.01);*/


	}

	function action_status() {

			//1) Выбираем новые платежи квики
			$q = QIWI::getInstance($this->qiwiConfig);

			$invoice_list =  DB::select("id")
					        ->from('invoice')
					        ->where('status', '=', 'N')
			                ->where('payment_id', '=', 2)
					        ->execute()->as_array();


			$i_request = array();
			foreach($invoice_list as $i) {
				$i_request[] = $i['id'];
			}

			$q_result = $q->billStatus($i_request,true);
			$email = "";
			foreach( $q_result as $id => $v) {

				if($v['status'] != 60) continue; //  не оплачен


				$content = "Order:$id\nAmount:{$v['amount']}\nDetails: ".print_r($v,true)."\n\n";
				$status = "success";

				try {
					$invoice = ORM::factory('invoice',$id);
					if(!$invoice->loaded()) throw new Exception("Cannot find invoice (".$id.")");
					// проверяем фактические параметры
					if ( floatval($invoice->amount) != floatval($v['amount']))
						throw new Exception("Payment Data error ({$v['amount']} != $invoice->amount)");
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

				DB::insert('invoice_log', array('invoice_id', 'status', 'content', 'cdate'))->values(array($id, $status, $content, Date::formatted_time()))->execute();
				$email.="<br>$content<br><hr><br>\n";



			}

			if(!empty($email)) {
//				Email::send('sekretar@as-avtoservice.ru', array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), $feedback->title, $email_view, TRUE);
				Email::send('spam0@newway.com.ua', array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), "Уведопление об платежах QIWI", $email, TRUE);
				Email::send('sekretar@as-avtoservice.ru', array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), "Уведопление об платежах QIWI", $email, TRUE);
			}


		exit;

	}


	public static function render_form($invoice_id, $amount, $currency = "RUB") {

		$merchant_id = "201721";
		$amount = sprintf("%.2f", $amount);

		$desc = "Prepaid acount access (invoice: UPI - {$invoice_id})";


		$paymentForm = '<form action="http://w.qiwi.ru/setInetBill_utf.do" method="get" accept-charset="utf-8" onSubmit="return checkSubmit();">

			<input type="hidden" name="from" value="'.$merchant_id.'"/>
			<input type="hidden" name="summ" value="'.$amount.'"/>
			<input type="hidden" name="txn_id" value="'.$invoice_id.'"/>
			<input type="hidden" name="lifetime" value="240"/>
			<input type="hidden" name="com" value="'.$desc.'"/>
			<input type="hidden" name="check_agt" value="false"/>
			<table>
				<tr>
					<td style="width:200px; text-align:center; padding:10px 0px;">Мобильный телефон<br>(пример: 9057772233)</td>
					<td style="padding:10px">
						<input type="text" name="to" id="idto" style="width:130px; border: 1px inset #555;"></input>
						<span id="div_idto"></span>
						<script type="text/javascript">
							inputMasks["idto"] = new Mask(document.getElementById("idto"));
							function checkSubmit() {
								if (inputMasks["idto"].getValue().match(/^\d{10}$/)) {
									document.getElementById("idto").setAttribute("disabled", "disabled");
									inputMasks["idto"].makeHInput();
									return true;
								} else {
									alert("Введите номер телефона в федеральном формате без \"8\" и без \"+7\"");
									return false;
								}
							}
						</script>
	                </td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" value="Оплатить в системе QIWI" style=" padding:10px 0;border:none; background:url(https://ishop.qiwi.ru/img/button/superBtBlue.jpg) no-repeat 0 50%; color:#fff; width:300px;"/>
					</td>
				</tr>
			</table>';

		return $paymentForm;
	}
}