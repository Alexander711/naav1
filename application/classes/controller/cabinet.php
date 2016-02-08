<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet extends Controller_Frontend {

	public $template = 'templates/frontend';
	var $allow_expired = false;


	function before() {

		parent::before();


		if (!$this->auth->logged_in()) {
			Message::set(Message::ERROR, 'Доступ закрыт, авторизуйтесь');
			$this->request->redirect('login');
		}

		if(empty($this->user->expires)) {
			$user_expires = new DateTime();
			$user_expires->modify("-1 day");
		} else {
			$user_expires = new DateTime($this->user->expires);
		}

		//  проверка на позможность использования кабинета
		/*if (!$this->allow_expired && Date::diff($user_expires->format("Y-m-d 23:59:59"),date("Y-m-d")) <= 0 ) {
			Message::set(Message::ERROR, 'Аккаунт заблокирован. Пожалуйста, пополните счет');
			$this->request->redirect('/cabinet/payment/add');
		}*/

		if (!empty($this->user->expires)) {
			$exp = Date::diff($user_expires->format("Y-m-d"), date("Y-m-d"), 'days');
			$exp++; // текущий день - опказываем как день

			if ($exp >= 0 && $exp <= 5)
				Message::set(Message::NOTICE, 'Внимание, осталось <strong>' . $exp . " ". MyHelper::morph($exp,"день","дня","дней").' </strong> использования аккаунтом');
		}


		$this->template->bc['cabinet'] = 'Личный кабиент';
	}

	function after() {


		$new_notice = '';
		if (isset($this->template->notice_count['new']) AND $this->template->notice_count['new'] > 0) {
			$new_notice = ' <span style="background-color: #d8a04b; color: #FFF; text-decoration: none; padding: 0 5px;">' . $this->template->notice_count['new'] . '</span>';
		}
		$menu = array(
			'main'       => array(
				'url'   => 'cabinet',
				'title' => 'Панель управления',
				'icon'  => 'assets/img/icons/home.png'
			),
			'profile'    => array(
				'url'   => 'cabinet/profile',
				'title' => __('cb_profile'),
				'icon'  => 'assets/img/icons/profile_mini.png'
			),
			'company'    => array(
				'url'   => 'cabinet/company',
				'title' => __('cb_companies'),
				'icon'  => 'assets/img/icons/services_mini.png'
			),
			'statistics' => array(
				'url'   => 'cabinet/statistics',
				'title' => 'Статистика',
				'icon'  => 'assets/img/icons/statistics.png'
			),
			'gallery'    => array(
				'url'   => 'cabinet/gallery',
				'title' => 'Галерея',
				'icon'  => 'assets/img/icons/gallery.png'
			),
			'news'       => array(
				'url'   => 'cabinet/news',
				'title' => __('cb_news'),
				'icon'  => 'assets/img/icons/news_mini.png'
			),
			'vacancy'    => array(
				'url'   => 'cabinet/vacancy',
				'title' => __('cb_vacancies'),
				'icon'  => 'assets/img/icons/vacancies_mini.png'
			),
			'stock'      => array(
				'url'   => 'cabinet/stock',
				'title' => __('cb_stocks'),
				'icon'  => 'assets/img/icons/stocks_mini.png'
			),
			'qa'         => array(
				'url'   => 'cabinet/qa',
				'title' => __('cb_questions'),
				'icon'  => 'assets/img/icons/questions.png'
			),
			'adv'        => array(
				'url'   => 'cabinet/adv',
				'title' => __('cb_adv'),
				'icon'  => 'assets/img/icons/adv_mini.png'
			),
			'feedback'   => array(
				'url'   => 'cabinet/feedback',
				'title' => __('cb_feedback'),
				'icon'  => 'assets/img/icons/feedback.png'
			),
			'notice'     => array(
				'url'   => 'cabinet/notice',
				'title' => __('cb_notice') . $new_notice,
				'icon'  => 'assets/img/icons/notices_mini.png'
			),
			'payment'     => array(
				'url'   => 'cabinet/payment/',
				'title' => __('cb_payment'),
				'icon'  => 'assets/img/icons/ccard.png'
			)
		);
		$cabinet_template = View::factory('templates/cabinet')
				->set('menu_items', $menu)
				->set('content', $this->template->content)
				->set('notice_count', $this->template->notice_count)
				->set('expires', empty($this->user->expires) ? null:MyDate::show_small($this->user->expires))
				->render();
		$this->template->content = $cabinet_template;
		parent::after();
	}
}