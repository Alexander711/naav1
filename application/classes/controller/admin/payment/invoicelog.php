<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Admin_Payment_Invoicelog extends Controller_Backend {

	public function before() {

		parent::before();
		$this->template->bc['admin/payment/invoice_log'] = 'История проводок';
	}

	function action_index() {

		$invoice_log = ORM::factory('invoicelog');

		$count = $invoice_log->count_all();



		// передаем значение количества товаров в модуль pagination и формируем ссылки
		$pagination = Pagination::factory(
			array('total_items' => $count, 'items_per_page' => 10,)
		);

		$invoice_log->limit($pagination->items_per_page)
									->offset($pagination->offset);


		$this->view = View::factory('backend/payment/invoicelog')
				->set('invoice_log', $invoice_log)
				->set('pagination', $pagination);

		$this->template->title = 'История проводок';
		$this->template->content = $this->view;



	}



}