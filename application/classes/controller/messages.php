<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Messages extends Controller_Frontend {

	public $template = 'templates/frontend_news';

	private $_car_brands = array();
	private $_car_models = array();

	function before() {

		parent::before();
		$this->template->bc['messages'] = 'Запросы пользователей';
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
	}

	function action_index() {

		$qa = ORM::factory('question');
		$this->view = View::factory('frontend/qa/all')
				->set('qa', $qa);
		$this->template->title = 'Запросы пользователей';
		$this->template->content = $this->view;

	}

	function action_view() {

		$qa = ORM::factory('question', $this->request->param('id', null));
		if (!$qa->loaded() OR $qa->active == 0) {
			$this->request->redirect('/');
		}
		$works = array();
		foreach ($qa->works->find_all() as $work) {
			$works[] = $work->name;
		}
		$this->view = View::factory('frontend/qa/view')
				->set('qa', $qa)
				->set('works', $works);
		$this->template->title = 'Запрос пользователя';
		$this->template->bc['#'] = 'Запрос на ' . $qa->carbrand->name . ' ' . $qa->model->name . ' ' . $qa->volume . ' ' . $qa->gearbox->name . ' ' . $qa->year;
		$this->template->content = $this->view;
	}

	/**
	 * Добавление запроса
	 * @return
	 */
	public function action_add() {

		$service = ORM::factory('service', $this->request->query('service'));

		$this->_car_brands = ORM::factory('car_brand')->get_cars_as_array();
		$this->_car_models = ORM::factory('car_model')->get_models();
		$cities = ORM::factory('city')->get_all_cities();
		$gearboxes = ORM::factory('gearbox')->get_gearboxes();
		$work = ORM::factory('workcategory');

		$this->view = View::factory('frontend/qa/add')
				->bind('values', $this->values)
				->bind('errors', $this->errors)
				->bind('car_models', $this->_car_models)
				->bind('car_brands', $this->_car_brands)
				->set('gearboxes', $gearboxes)
				->set('cities', $cities)
				->set('service', $service)
				->set('work_category', $work);

		$this->template->title = 'Добавление запроса';
		$this->template->bc['#'] = $this->template->title;
		$this->template->content = $this->view;

		if ($_POST) {
			$image_url = '';
			if ($_FILES['image'] AND $_FILES['image']['name'] != '') {

				$this->validation = Validation::factory($_FILES)
						->rule('image', 'Upload::not_empty')
						->rule('image', 'Upload::type', array(':value', array('jpg', 'png', 'gif')));
				if ($this->validation->check()) {
					$image_name = md5(date('YmdHis')) . '.' . $this->my_exts($_FILES['image']['type']);
					Upload::save($_FILES['image'], $image_name, 'upload/attachments/qa');
					$image_url = 'upload/attachments/qa/' . $image_name;
				} else {
					$this->errors = $this->validation->errors('models/question');
					$this->values = $_POST;

					return;
				}

			}

			// Если выбрали марко авто
			if (isset($_POST['car_id']) AND $_POST['car_id'] AND array_key_exists($_POST['car_id'], $this->_car_brands)) {
				$this->_car_models = ORM::factory('car_model')->get_models($_POST['car_id']);
			}

			$qa = ORM::factory('question');
			try {
				$qa->values($_POST, array('contact', 'text', 'email', 'car_id', 'model_id', 'city_id', 'gearbox_id', 'year', 'vin', 'volume', 'phone'));

				$qa->image = $image_url;
				$qa->date = Date::formatted_time();
				$qa->for_service_has_car = Arr::get($_POST, 'for_service_has_car', 0);
				$qa->for_service_address = Arr::get($_POST, 'for_service_address', 0);
				$qa->active = 0;
				$qa->save();

				$works = Arr::get($_POST, 'work', array());
				foreach ($works as $work) {
					$qa->add('works', $work);
				}


				if ($service->loaded()) {
					$qa->add('services', $service->id);
				}
				if ($qa->for_service_has_car == 1 OR $qa->for_service_address == 1) {
					$service = ORM::factory('service');

					foreach ($service->find_all() as $s) {
						$add_this_service = false;

						if ($qa->for_service_has_car == 1 AND $s->has('cars', $qa->car_id)) {
							$add_this_service = true;
						}

						if ($qa->for_service_address == 1 AND $s->city_id == $qa->city_id) {
							$add_this_service = true;
						}
						if ($add_this_service && $s->id != $service->id) {
							$qa->add('services', $s->id);
						}
					}
				}


				Message::set(Message::SUCCESS, __('qa_adding_complete'));
				$this->request->redirect('messages');
			} catch (ORM_Validation_Exception $e) {
				$this->errors = $this->errors + $e->errors('models');
				$this->values = $_POST;
				// Удаляем ранее загруженное изображение
				if ($image_url != '' AND file_exists($image_url)) {
					unlink($image_url);
				}
			}
		}


	}

	private function my_exts($mime) {

		$ext = '';
		switch ($mime) {
			case "image/bmp":
				$ext = 'bmp';
				break;

			case "image/x-windows-bmp":
				$ext = 'bmp';
				break;

			case "image/gif":
				$ext = "gif";
				break;

			case "image/jpeg":
				$ext = "jpg";
				break;

			case "image/png":
				$ext = "png";
				break;
		}

		return $ext;
	}
}