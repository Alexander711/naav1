<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cron extends Controller
{
	function before()
	{
		parent::before();
		$this->response->headers('Pragma', 'no-cache');
		$last_modified = Date::formatted_time();
		$this->response->headers('Cache-Control', 'no-store, no-cache, must-revalidate');
		$this->response->headers('Last-Modified', gmdate("D, d M Y H:i:s \G\M\T", strtotime($last_modified)));
	}
    function action_index()
    {

    }
    public function action_cron_test_1()
    {
        DB::insert('cron_test_1', array('str', 'date'))->values(array('Тест №1 от '.Date::formatted_time(), Date::formatted_time()))->execute();
        $this->request->redirect('cron/cron_test_2');
    }
    public function action_cron_test_2()
    {
        DB::insert('cron_test_2', array('str', 'date'))->values(array('Тест №2 от '.Date::formatted_time(), Date::formatted_time()))->execute();
    }
    /**
     * Отправка на почту уведомлений
     * @return void
     */
    public function action_notice_email_sender()
    {
        if (Arr::get($_GET, 'start') != 'email')
            return;


        $message = ORM::factory('emailsender')
                      ->where('status', '=', 'queue')
                      ->limit(40);
        $message->reset(FALSE);
        if (count($message->find_all()) > 0)
        {
            Email::connect();
        }
        $date = Date::formatted_time();
        foreach ($message->find_all() as $m)
        {
            Email::send($m->mail_to, array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), $m->title, $m->text, TRUE);
            echo "date: ".$date." message_id: ".$m->id.", title: ".$m->title.", send to: ".$m->mail_to.", send from: ".$m->mail_from." \n";
            $m->date_send = $date;
            $m->status = 'send';
            $m->update();
        }
    }
    /**
     * Обновление географического кеша
     * @return void
     */
    public function action_geo_cache_updater()
    {
        if (Arr::get($_GET, 'start') != 'geo_cache')
            return;
        if (Geography::update_geography_params())
            echo "Географический кэш успешно обновлен в ".Date::formatted_time()." \n";
        else
            echo "Ошибка обновления географического кэша \n";
    }


	public function action_process_user_payments()
    {
	    // уведомляем пользователей, у которых осталось мало времени пользования аккаунтом
        $disable_event_period = DB::select('days')
                                ->from('payment_settings')
                                ->where('status', '=', 'N') // оплаченый платеж
                                ->where('system_name', '=', 'disable_event_period') // вступ. взнос
                                ->limit(1)
                                ->execute()->get('days');
        if(empty($disable_event_period)) $disable_event_period = 5;

        $date = new DateTime();
	    $date->modify(($disable_event_period-1)." days");

	    $exp = ORM::factory('user')
                      ->where(DB::expr('DATE(expires)'), '=', $date->format("Y-m-d"));


	    $exp->reset(FALSE);
        if (count($exp->find_all()) > 0)
        {
            Email::connect();


			$exp_date = $date->format("d.m.Y");
	        $title= "Действие вашего аккаунта заканчивается";
	        $text= "Добрый день, %s.<br><br>Действие вашего аккаунта заканчивается %s.<br>Вы можете продлить аккаунт в <a href='http://www.as-avtoservice.ru/cabinet/payment'>личном кабинете</a>";

            foreach ($exp->find_all() as $e)
            {
                Email::send($e->email , array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), $title, sprintf($text, $e->username, $exp_date), TRUE);
                echo "date: ".$exp_date." email: ".$e->email."\n";
            }
        }


	    //  1) ставим статус просрочен всем

	    $payment_expires_period = DB::select('days')
	    				        ->from('payment_settings')
	    				        ->where('status', '=', 'N') // оплаченый платеж
	    		                ->where('system_name', '=', 'payment_expires_period') // вступ. взнос
	    				        ->limit(1)
	    				        ->execute()->get('days');
	    if(empty($payment_expires_period)) $payment_expires_period = 5;

	    $date = new DateTime();
	    $date->modify("-".$payment_expires_period." days");

        DB::update("invoice")
                ->set(array('status' => 'E'))
                ->where('status','=','N')
                ->where('create_date','<',$date->format("Y-m-d"))
                ->execute();

	    $date = new DateTime();
	    //  Вимикаєм користувачів з просроченими кабінетами
	    $query = DB::update("users")
                    ->set(array('user_type' => 'disabled'))
	                ->where_open()
                    ->where('expires','<',$date->format("Y-m-d"))
                    ->or_where('expires','=',NULL)
	                ->where_close()
                    ->execute();


    }


	public function action_process_payment_status() {
//		Email::send("spam0@newway.com.ua" , array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), "Начало обработки платежей","", TRUE);
		//  обработаем статус квики
		file_get_contents("http://www.as-avtoservice.ru/payment/qiwi/status");


	}



    /*
    public function action_notice_email_sender()
    {
        $counter = 1;
        $subscribe = DB::select('id', 'user_id', 'notice_id')->from('notice_subscribe')->where('status', '=', 'queue')->limit(40)->execute();
        if (count($subscribe) > 0)
        {
            Email::connect();
        }
        foreach ($subscribe as $s)
        {
            $notice = DB::select('title', 'text', 'id')->from('notices')->where('id', '=', $s['notice_id'])->execute()->current();
            $user = DB::select('username', 'email')->from('users')->where('id', '=', $s['user_id'])->execute()->current();
            if ($user AND $notice)
            {
                $email_view = View::factory('email/notice_created')
                                  ->set('username', $user['username'])
                                  ->set('title', $notice['title'])
                                  ->set('text', $notice['text'])
                                  ->render();
                Email::send($user['email'], array('no-reply@as-avtoservice.ru', 'Ассоциация автосервисов'), 'Уведомление от ассоциации автосервисов', $email_view, TRUE);
                echo "date: ".Date::formatted_time()." subscribe_id: ".$s['id']." notice_id: ".$notice['id']." email send to ".$user['email']." (".$user['username'].") \n";
                DB::update('notice_subscribe')->set(array('status' => 'send'))->where('id', '=', $s['id'])->execute();
            }
            else
            {
                echo "date: ".Date::formatted_time()." error! subscribe_id: ".$s['id']." notice_id: ".$notice['id']." not send to ".$user['email']." (".$user['username']."\n";
                DB::update('notice_subscribe')->set(array('status' => 'error'))->where('id', '=', $s['id'])->execute();
            }
        }
    }
    */
}