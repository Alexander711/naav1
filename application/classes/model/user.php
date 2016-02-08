<?php
defined('SYSPATH') or die('No direct script access.');
class Model_User extends Model_Auth_User {

	protected $_has_one = array(
		'info' => array(
			'model'       => 'userinfo',
			'foreign_key' => 'user_id',
		),
	);
	protected $_table_columns = array(
		'id'                 => null,
		'username'           => null,
		'email'              => null,
		'password'           => null,
		'date_create'        => null,
		'logins'             => null,
		'last_logins'        => null,
		'user_type'          => null,
		'email_confirm_code' => null,
		'expires'            => null
	);

	protected $_has_many = array(
		'user_tokens'              => array('model' => 'user_token'),
		'roles'                    => array('model' => 'role', 'through' => 'roles_users'),
		'feedbacks'                => array(
			'model'       => 'feedback',
			'foreign_key' => 'user_id'
		),
		'services'                 => array(
			'model'       => 'service',
			'foreign_key' => 'user_id'
		),
		'news_service'             => array(
			'model'       => 'newsservice',
			'foreign_key' => 'user_id'
		),
		'stocks'                   => array(
			'model'       => 'stock',
			'foreign_key' => 'user_id'
		),
		'vacancies'                => array(
			'model'       => 'vacancy',
			'foreign_key' => 'user_id'
		),
		'answers'                  => array(
			'model'       => 'answer',
			'foreign_key' => 'user_id'
		),
		'reviews'                  => array(
			'model'       => 'review',
			'foreign_key' => 'user_id'
		),
		'notices'                  => array(
			'model'       => 'notice',
			'foreign_key' => 'user_id',
			'through'     => 'notices_users',
			'far_key'     => 'notice_id'
		),
		// Связующая таблица к уведомлениям для пользователей
		'notices_users_through'    => array(
			'model'       => 'notices_users',
			'foreign_key' => 'user_id'
		),
		'notices_services_through' => array(
			'model'       => 'notices_services',
			'foreign_key' => 'user_id'
		),
		'notice_subscribe'         => array(
			'model'       => 'subscribe_notice',
			'foreign_key' => 'user_id'
		),
		'message_subscribe'        => array(
			'model'       => 'subscribe_message',
			'foreign_key' => 'user_id'
		),
		'invoices'                  => array(
			'model'       => 'invoice',
			'foreign_key' => 'user_id',
		),
	);

	public function rules() {

		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'email'    => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
			'expires'  => array(
				array('date')
			),
		);
	}

	public function filters() {

		return array(
			'username' => array(
				array('trim')
			),
			'email'    => array(
				array('trim')
			),
			'password' => array(
				array('trim'),
				array(array(Auth::instance(), 'hash'))
			),
			'expires'  => array(
				array('Date::formatted_time'),
			)
		);
	}

	/**
	 * Получаем ID всех имеющихся у пользователя сервисов. Нужно для where in
	 * @return array
	 */
	public function get_user_services_ids() {

		$ids = array();
		foreach ($this->services->find_all() as $service) {
			$ids[] = $service->id;
		}

		return $ids;
	}

	static function available_user($value) {

		return (bool)DB::select(array('COUNT("*")', 'total_count'))->from('users')->where('id', '=', $value)->execute()->get('total_count');
	}

	/**
	 * Удаление всех данных пользователя
	 * @return Model_User
	 */
	public function delete_data() {

		$this->remove('notices');
		foreach ($this->reviews->find_all() as $r)
			$r->delete();
		foreach ($this->feedbacks->find_all() as $f)
			$f->delete();
		foreach ($this->services->find_all() as $s)
			$s->delete_data()->delete();

		return $this;
	}
}