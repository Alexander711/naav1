<?php

defined('SYSPATH') or die('No direct script access.');
class Model_Payment_Settings extends ORM
{
	protected $_table_name = 'payment_settings';
	protected $_table_columns = array(
		'id'      => null,
		'name'    => null,
		'price'   => null,
		'days'    => null,
		'in_list' => null,
		'status'  => null,
		'sort'    => null,
		'system'    => null
	);


	protected $_belongs_to = array();
	protected $_has_many = array();

	public function rules()
	{
		return array(
			'title'       => array(array('not_empty')),
			'text'        => array(array('not_empty')),
			'date_create' => array(array('not_empty')),
			'service_id'  => array(
				array('not_empty'),
				array(array($this, 'check_service'), array(':value'))
			)
		);
	}

	public function filters()
	{
		return array(
			'title'       => array(array('trim')),
			'text'        => array(
				array('trim')
			),
			'date_create' => array(array('trim')),
			'date_edited' => array(array('trim')),
		);
	}

	public function site_search($words)
	{
		if (empty($words)) {
			return false;
		}
		$this->where('active', '=', 1);
		$this->and_where_open();
		foreach ($words as $w) {
			$this->or_where('title', 'LIKE', '%' . $w . '%')
					->or_where('text', 'LIKE', '%' . $w . '%');
		}
		$this->and_where_close();

		return $this;
	}

	/**
	 * Проверка на валидность указываемого сервиса.
	 * Пользователь может создать новость только к своим сервисам.
	 * Исключение админ
	 * @return void
	 */
	public function check_service($value)
	{
		$user = Auth::instance()->get_user();
		if (Auth::instance()->logged_in('admin')) {
			return true;
		}
		if (!$user OR DB::select('user_id')->from('services')->where('id', '=', $value)->execute()->get('user_id') != $user->id) {
			return false;
		}

		return true;
	}

	/**
	 * Получаем активные новости компаний
	 * @param string $date_sort
	 * @return Database_Result
	 */
	public function get_news($date_sort = 'DESC')
	{
		return $this->where('active', '=', 1)->order_by('date_create', $date_sort)->find_all();
	}
}