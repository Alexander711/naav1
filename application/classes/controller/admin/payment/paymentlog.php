<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Payment_Paymentlog extends Controller_Backend {

	public function before() {

		parent::before();
		$this->template->bc['admin/payment/payment_log'] = 'История начислений';
	}

	function action_index() {
		$log = ORM::factory('paymentlog');

		$count = $log->count_all();



		// передаем значение количества товаров в модуль pagination и формируем ссылки
		$pagination = Pagination::factory(
			array('total_items' => $count, 'items_per_page' => 10,)
		);

		$log->limit($pagination->items_per_page)
									->offset($pagination->offset);


		$this->view = View::factory('backend/payment/paymentlog')
				->set('log', $log)
				->set('pagination', $pagination);

		$this->template->title = 'История начислений';
		$this->template->content = $this->view;



	}


}