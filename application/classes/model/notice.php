<?php
defined('SYSPATH') or die('No direct script access.');
class Model_Notice extends ORM {

	protected $_table_name = 'notices';
	protected $_table_columns = array(
		'id'          => null,
		'type'        => null,
		'for'         => null,
		'description' => null,
		'title'       => null,
		'text'        => null,
		'date'        => null,
		'date_edited' => null
	);
	protected $_has_many = array(
		'services'         => array(
			'model'   => 'service',
			'through' => 'notices_services'
		),
		'users'            => array(
			'model'   => 'user',
			'through' => 'notices_users'
		),
		// Для доступа к связующим таблицам
		'notices_services' => array(
			'model'       => 'notices_services',
			'foreign_key' => 'notice_id'
		),
		'notices_users'    => array(
			'model'       => 'notices_users',
			'foreign_key' => 'notice_id'
		),
		// Отправщик писем
		'subscribes'       => array(
			'model'       => 'subscribe_notice',
			'foreign_key' => 'notice_id'
		)
	);
	protected $_has_one = array(
		'notice_test' => array(
			'model'       => 'notices_services',
			'foreign_key' => 'notice_id'
		)
	);
	/**
	 * Флаг на сохранение статусов
	 * @var bool
	 */
	public $save_statuses = true;
	/**
	 * Список компаний
	 * @var array
	 */
	public $services_list = array(
		'selected_services'  => array(),
		'to_add_services'    => array(),
		'to_remove_services' => array(),
		'added_services'     => array(),
		'not_added_services' => array(),
		'all_services'       => array()
	);
	/**
	 * Список пользователей
	 * @var array
	 */
	public $users_list = array(
		'selected_users'  => array(),
		'to_add_users'    => array(),
		'to_remove_users' => array(),
		'to_reread_users' => array(),
		'added_users'     => array(),
		'not_added_users' => array(),
		'all_users'       => array()
	);

	private $_notice_count = array(
		'new'    => 0,
		'reread' => 0,
		'remove' => 0
	);

	function rules() {

		return array(
			'text' => array(array('not_empty'))
		);
	}

	function filters() {

		return array(
			'title' => array(array('trim')),
			'text'  => array(array('trim'))
		);
	}

	/**
	 * Возвращает массив кол-ва уведомлений {0 => новые, 1 => все}
	 * @param Model_User $user
	 * @return array
	 */
	public function get_notices_count(Model_User $user) {

		$result = array();
		$services = $user->get_user_services_ids();
		$query = DB::select()->from('notices')
				->join(array('notices_services', 'notices_services'), 'left')
				->on('notices.id', '=', 'notices_services.notice_id')
				->join(array('notices_users', 'notices_users'), 'left')
				->on('notices.id', '=', 'notices_users.notice_id')
				->and_where_open()
				->where('notices_users.user_id', '=', $user->id);
		if (!empty($services)) {
			$query->or_where('notices_services.service_id', 'in', $services);
		}

		$query->and_where_close();

		$result['new'] = count($query->and_where_open()->and_where('notices_services.read', '=', 'n')->or_where('notices_users.read', '=', 'n')->and_where_close()->execute());
		$result['total'] = count($query->execute());

		return $result;
	}

	/**
	 * Возврващает все доступные уведомления для пользователя
	 * @param Model_User $user
	 * @return array
	 */
	public function get_notices(Model_User $user) {

		$notices = array();
		$notice_config = Kohana::$config->load('notices_types');
		$notices_types = $notice_config['types'];
		$services = $user->get_user_services_ids();

		$selected_columns = $notice_config['selected_columns'];
		$query = DB::select()->from('notices')
				->select_array($selected_columns)
				->join(array('notices_services', 'notices_services'), 'left')
				->on('notices.id', '=', 'notices_services.notice_id')
				->join(array('notices_users', 'notices_users'), 'left')
				->on('notices.id', '=', 'notices_users.notice_id')
				->where('notices_users.user_id', '=', $user->id);

		if (!empty($services)) {
			$query->or_where('notices_services.service_id', 'in', $services);
		}

		$iteration = 0;
		foreach ($query->execute() as $q) {
			$config = $notices_types[$q['type']];
			if ($iteration == $q['id'])
				$iteration++;

			$id = ($config['unique'] == false) ? $q['id'] : $iteration;

			if (!isset($notices[$id]))
				$notices[$id] = array(
					'title' => $q['title'],
					'text'  => $q['text'],
					'date'  => $q[$config['date_column']],
					'read'  => $q[$config['read_column']]
				);


			if ($config['for'] == 'service') {
				$service = DB::select(array('services.name', 'name'), array('org_type.name', 'org_type'))
						->from('services')
						->join(array('org_types', 'org_type'))
						->on('org_type.id', '=', 'services.org_type')
						->where('services.id', '=', $q['service_id'])
						->execute()->current();
				$notices[$id]['for'][] = $service['org_type'] . ' ' . '&laquo;' . $service['name'] . '&raquo;';
			}

			$iteration++;
		}
		usort($notices, array($this, 'sort_func'));

		return $notices;
	}

	/**
	 * Сортировка уведомлений по дате
	 * @param $a
	 * @param $b
	 * @return int
	 */
	public function sort_func($a, $b) {

		if ($a['date'] == $b['date'])
			return 0;

		return ($a['date'] > $b['date']) ? -1 : 1;
	}

	public function set_notice_count($type, $count) {

		if (array_key_exists($type, $this->_notice_count))
			$this->_notice_count[$type] = $count;
	}

	public function get_notice_count($type) {

		if (array_key_exists($type, $this->_notice_count))
			return $this->_notice_count[$type];
		else
			return null;
	}

	/**
	 * Возвращаем все сервисы
	 * @return array
	 */
	public function get_services_users_ids() {

		foreach ($this->services->find_all() as $service) {
			$this->services_list['added_services'][$service->id] = array(
				'user_id'      => $service->user->id,
				'username'     => $service->user->username,
				'email'        => $service->user->email,
				'service_name' => $service->name
			);
		}

		$selected_columns = array(
			array('services.id', 'service_id'),
			array('services.name', 'service_name'),
			array('users.id', 'user_id'),
			array('users.username', 'username'),
			array('users.email', 'email')
		);
		$query = DB::select_array($selected_columns)
				->from('services')
				->join(array('users', 'users'))
				->on('services.user_id', '=', 'users.id');
		if (!empty($this->services_list['added_services'])) {
			$current_services_ids = array_keys($this->services_list['added_services']);
			$query->where('services.id', 'not in', $current_services_ids);
		}

		foreach ($query->execute() as $q) {
			$this->services_list['not_added_services'][$q['service_id']] = array(
				'user_id'      => $q['user_id'],
				'username'     => $q['username'],
				'email'        => $q['email'],
				'service_name' => $q['service_name']
			);
		}

		if (empty($this->services_list['added_services']))
			$this->services_list['all_services'] = $this->services_list['not_added_services'];
		else
			$this->services_list['all_services'] = $this->services_list['added_services'] + $this->services_list['not_added_services'];

	}

	/**
	 * Возвращаем всех пользователей кому отправлены уведомления
	 * @return array
	 */
	public function get_users_ids() {

		foreach ($this->users->find_all() as $user) {
			$this->users_list['added_users'][$user->id] = array(
				'username' => $user->username,
				'email'    => $user->email
			);
		}
		$query = DB::select()
				->from('users');
		if (!empty($this->users_list['added_users'])) {
			$current_users_ids = array_keys($this->users_list['added_users']);
			$query->where('id', 'not in', $current_users_ids);
			$this->users_list['all_users'] = $this->users_list['added_users'];
		}
		foreach ($query->execute() as $q) {
			$this->users_list['not_added_users'][$q['id']] = array(
				'username' => $q['username'],
				'email'    => $q['email']
			);
		}
		$this->users_list['all_users'] = $this->users_list['all_users'] + $this->users_list['not_added_users'];
	}

	/**
	 * Обновление статусов на непрочитано
	 * @param $notice_id
	 * @param string $type
	 * @return Database_Result|object
	 */
	public function update_to_unread($type = 'service') {

		$table_name = ($type == 'service') ? 'notices_services' : 'notices_users';

		return DB::update($table_name)
				->set(array('read' => 'n'))
				->where('notice_id', '=', $this->id)
				->and_where('read', '=', 'y')
				->execute();
	}

	/**
	 * Функция отправки сообшений на email о новых уведомлениях.
	 * Так же сносит ненужные email
	 * Возвращает массив кол-ва отправленных сообщений
	 * @return array
	 */
	public function send_email() {

		$mail_count = array(
			'new'     => 0,
			'resend'  => 0,
			'deleted' => 0
		);
		$subscribe = ORM::factory('subscribe_notice');
		/* Очистка лишних сообщений в очереди
		 * Сперва выборка всех id пользователей выбранных сервисов
		 */
		$selected_users = array();
		if ($this->type == 'service') {
			$list_name = (!empty($this->services_list['selected_services'])) ? 'selected_services' : 'all_services';
			foreach ($this->services_list[$list_name] as $service) {
				$selected_users[$service['user_id']] = array(
					'username' => $service['username'],
					'email'    => $service['email']
				);
			}
		} elseif ($this->type == 'user') {
			$list_name = (!empty($this->users_list['selected_users'])) ? 'selected_users' : 'all_users';
			foreach ($this->users_list[$list_name] as $user_id => $user) {
				$selected_users[$user_id] = array(
					'username' => $user['username'],
					'email'    => $user['email']
				);
			}
			unset($user);
		}



		$user_ids = array_keys($selected_users);
		if (!empty($user_ids)) {
			$subscribe->where('notice_id', '=', $this->id)
					->and_where('user_id', 'not in', $user_ids);
			// Удаляем уведомления для НЕ выбранных пользователей
			// Это относится и к уведомлениям для компаний и для пользователей
			// Если это уведомления для компаний, берутся хозяева компаний, то есть пользователи добавившие компании
			// Если это уведомление для пользователей, то тут просто выбранные пользователи или все
			// Удаляются только сообщения со статусом queue, отправленные сообщения остаются на память потомкам
			foreach ($subscribe->find_all() as $s) {
				if ($s->email->status == 'queue') {
					$s->email->delete();
					$mail_count['deleted']++;
				}
				$s->delete();
			}
		}
		$date = (!$this->date_edited OR $this->date_edited == '0000-00-00 00:00:00') ? $this->date : $this->date_edited;
		$email_view = View::factory('email/notice')
				->set('title', $this->title)
				->set('text', $this->text);


		foreach ($selected_users as $user_id => $user) {

			$subscribe = ORM::factory('subscribe_notice')
					->where('notice_id', '=', $this->id)
					->and_where('user_id', '=', $user_id)
					->find();

			if (!$subscribe->loaded()) {
				$subscribe->notice_id = $this->id;
				$subscribe->user_id = $user_id;
				$subscribe->save();
				$mail_count['new']++;
			}





			if (empty($subscribe->email_id) || !$subscribe->email->loaded()) {
			if ($subscribe->loaded())
					$mail_count['resend']++;



				$email_view->set('username', $user['username'])
						->render();
				$email = ORM::factory('emailsender');
				$email->mail_from = 'no-reply@as-avtoservice.ru';
				$email->mail_to = $user['email'];
				$email->text = $email_view;
				$email->title = 'Уведомление от ассоциации автосервисов';
				$email->date_create = $date;
				$email->save();
				/*
				$subscribe->email->mail_from = 'no-reply@as-avtoservice.ru';
				$subscribe->email->mail_to = $user['email'];
				$subscribe->email->text = $email_view;
				$subscribe->email->title = 'Уведомление от ассоциации автосервисов';
				$subscribe->email->date_create = $date;
				$subscribe->email->save();
				*/
				$subscribe->email_id = $email->id;
				$subscribe->update();
			}
		}

		return $mail_count;
	}

	/**
	 * Отправка писем в очередь
	 * @param array $mail_list
	 * @return void
	 */
	public function email_sender() {

		$email_view = View::factory('email/notice')
				->set('title', $this->title)
				->set('text', $this->text);

		foreach ($this->subscribes_users as $user_id) {
			$subscribe = ORM::factory('subscribe_notice')
					->where('notice_id', '=', $this->id)
					->and_where('user_id', '=', $user_id)
					->find();
			if (!$subscribe->loaded()) {
				$subscribe->notice_id = $this->id;
				$subscribe->user_id = $user_id;
				$subscribe->save();
			}

			$date = ($this->date_edited == '0000-00-00 00:00:00') ? $this->date : $this->date_edited;

			if (!$subscribe->email->loaded() OR $subscribe->email->date_create != $date) {
				$email_view->set('username', $subscribe->user->username)
						->render();
				$subscribe->email->mail_from = 'no-reply@as-avtoservice.ru';
				$subscribe->email->mail_to = $subscribe->user->email;
				$subscribe->email->text = $email_view;
				$subscribe->email->title = 'Уведомление от ассоциации автосервисов';
				$subscribe->email->date_create = $date;
				$subscribe->email->save();
				$subscribe->email_id = $subscribe->email->id;
				$subscribe->update();
			}
		}
	}

	/**
	 * Валидация
	 * @static
	 * @return Kohana_Validation
	 */
	public static function services_validation() {

		return Validation::factory($_POST)
				->rule('service', 'Model_Notice::check_services', array(':value'));
	}

	/**
	 * Колбек функция для валидации
	 * @static
	 * @param array $value
	 * @return bool
	 */
	public static function check_services(Array $value) {

		return (count($value) == DB::select(array('COUNT("*")', 'total_count'))->from('services')->where('id', 'in', $value)->execute()->get('total_count'));
	}
}