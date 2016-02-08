<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Service extends ORM {

    protected $_reload_on_wakeup = FALSE;
    protected $_table_name = 'services';
    protected $_table_columns = array(
        'id' => NULL,
        'hints' => NULL,
        'basic_rate' => NULL,
        'rate' => NULL,
        'type' => NULL,
        'name' => NULL,
        'date_create' => NULL,
        'date_edited' => NULL,
        'user_id' => NULL,
        'org_type' => NULL,
        'inn' => NULL,
        'city_id' => NULL,
        'district_id' => NULL,
        'metro_id' => NULL,
        'address' => NULL,
        'phone' => NULL,
        'fax' => NULL,
        'email' => NULL,
        'site' => NULL,
        'work_times' => NULL,
        'about' => NULL,
        'director_name' => NULL,
        'contact_person' => NULL,
        'active' => NULL,
        'code' => NULL,
        'discount_id' => NULL,
        'coupon_text' => NULL,
        'ymap_lng' => NULL,
        'ymap_lat' => NULL
    );
    protected $_has_many = array(
        'feedbacks' => array(
            'model' => 'feedback',
            'foreign_key' => 'service_id'
        ),
        'visits' => array(
            'model' => 'visit',
            'through' => 'services_visits',
            'foreign_key' => 'service_id',
            'far_key' => 'visit_id'
        ),
        'works' => array(
            'model' => 'work',
            'through' => 'services_works'
        ),
        'cars' => array(
            'model' => 'car_brand',
            'through' => 'services_cars',
            'far_key' => 'car_id',
            'foreign_key' => 'service_id'
        ),
        'notices' => array(
            'model' => 'notice',
            'through' => 'notices_services'
        ),
        'stocks' => array(
            'model' => 'stock',
            'foreign_key' => 'service_id'
        ),
        'reviews' => array(
            'model' => 'review',
            'foreign_key' => 'service_id'
        ),
        'news' => array(
            'model' => 'newsservice',
            'foreign_key' => 'service_id'
        ),
        'vacancies' => array(
            'model' => 'vacancy',
            'foreign_key' => 'service_id'
        ),
        'answers' => array(
            'model' => 'answer',
            'foreign_key' => 'service_id'
        ),
        'images' => array(
            'model' => 'CompanyImage',
            'foreign_key' => 'company_id'
        ),
        'group' => array(
            'model' => 'group',
            'through' => 'services_groups',
            'foreign_key' => 'service_id',
            'far_key' => 'group_id',
        )
    );
    protected $_belongs_to = array(
        'user' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        ),
        'city' => array(
            'model' => 'city',
            'foreign_key' => 'city_id'
        ),
        'district' => array(
            'model' => 'district',
            'foreign_key' => 'district_id'
        ),
        'metro' => array(
            'model' => 'metro',
            'foreign_key' => 'metro_id'
        ),
        'discount' => array(
            'model' => 'discount',
            'foreign_key' => 'discount_id'
        ),
        'orgtype' => array(
            'model' => 'orgtype',
            'foreign_key' => 'org_type'
        ),
    );
    public $disable_validation = FALSE;
    private $_search_params = array();
    private $_has_items = array();

    const SHOP = 2;
    const AUTOSERVICE = 1;

    public static $type_urls = array(
        self::AUTOSERVICE => 'services',
        self::SHOP => 'shops'
    );
    // Параметры поиска компаний
    private $_site_search_params = array(
        'car_brands' => array(),
        'cities' => array(),
        'districts' => array(),
        'metro_stations' => array(),
        'works' => array(),
        'company_types' => array(),
        'params_count' => 0,
        'text_params' => array()
    );
    private $_original_words = array();
    private $_word_index_relations = array(
        'cities' => array(),
        'districts' => array(),
        'metro_stations' => array(),
        'company_types' => array(),
        'car_brands' => array(),
        'works' => array()
    );

    public static function generate_ratings() {
        $last_update_time = Cache::instance()->get('company_ratings_last_update_time');
        if (!$last_update_time OR abs(time() - $last_update_time) > Date::HOUR) {
            Cache::instance()->set('company_ratings_last_update_time', time());
            foreach (ORM::factory('service')->find_all() as $company) {
                $company->_update_rate();
            }
        }
    }

    protected function _update_rate() {
        $company_page_hints = count($this->visits->find_all()) + $this->hints;
        $company_news_page_hints = 0;
        $company_stocks_page_hints = 0;
        $company_vacancies_page_hints = 0;
        foreach ($this->news->find_all() as $news)
            $company_news_page_hints += count($news->visits->find_all()) + $news->hints;
        foreach ($this->stocks->find_all() as $stock)
            $company_stocks_page_hints += count($stock->visits->find_all()) + $stock->hints;
        foreach ($this->vacancies->find_all() as $vacancy)
            $company_vacancies_page_hints += count($vacancy->visits->find_all()) + $vacancy->hints;

        $this->rate = $this->basic_rate + $company_page_hints + $company_news_page_hints + $company_stocks_page_hints + $company_vacancies_page_hints;
        $this->disable_validation = TRUE;
        $this->update();
    }

    /**
     * Удаление данных компании
     * @return Model_Service
     */
    public function delete_data() {
        $this->remove('cars')
                ->remove('works')
                ->remove('notices');
        foreach ($this->feedbacks->find_all() as $f)
            $f->delete();
        foreach ($this->news->find_all() as $n) {
            foreach ($n->visits->find_all() as $visit) {
                $n->remove('visits', $visit->id);
                $visit->delete();
            }
            if ($n->image AND file_exists($n->image) AND is_writable($n->image)) {
                unlink($n->image);
                $name = explode('.', $n->image);
                unlink($name[0] . '_pict.' . $name[1]);
            }
            $n->delete();
        }
        foreach ($this->vacancies->find_all() as $v) {
            foreach ($v->visits->find_all() as $visit) {
                $v->remove('visits', $visit->id);
                $visit->delete();
            }
            $v->delete();
        }

        foreach ($this->stocks->find_all() as $s) {
            foreach ($s->visits->find_all() as $visit) {
                $s->remove('visits', $visit->id);
                $visit->delete();
            }
            $s->delete();
        }

        foreach ($this->reviews->find_all() as $r)
            $r->delete();
        foreach ($this->answers->find_all() as $a)
            $a->delete();
        foreach ($this->images->find_all() as $i) {
            if ($i->img_path AND file_exists($i->img_path) AND is_writable($i->img_path))
                unlink($i->img_path);
            if ($i->thumb_img_path AND file_exists($i->thumb_img_path) AND is_writable($i->thumb_img_path))
                unlink($i->thumb_img_path);
            $i->delete();
        }
        foreach ($this->visits->find_all() as $visit) {
            $this->remove('visits', $visit->id);
            $visit->delete();
        }

        return $this;
    }

    public function get_search_params() {
        return $this->_site_search_params;
    }

    /**
     * Указание параметра поиска
     * @param $field
     * @param $value
     * @return boolean
     */
    private function _set_search_param($field, $value, $word_index, $debug = NULL) {
        if (!in_array($value, $this->_site_search_params[$field])) {
            $this->_site_search_params[$field][] = $value;
            $this->_word_index_relations[$field][$value] = $word_index;

            $this->_site_search_params['params_count'] ++;

            // Debug
            $log = 'param ' . $field . ' added, value: ' . $value;
            if ($debug AND is_array($debug))
                foreach ($debug as $placeholder => $val)
                    $log .= ' ' . $placeholder . ': ' . $val;

            //FirePHP::getInstance(TRUE)->log($log);

            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param array $words
     * @param array $original_words
     * @return bool|object
     */
    public function search(Array $words, Array $original_words) {
        //$firephp = FirePHP::getInstance(TRUE);
        $this->_original_words = $original_words;
        // Построение параметров
        foreach ($words as $word_index => $word) {

            // Как минимум 4 символа для поиска города
            if (mb_strlen($word) > 3) {
                // Поиск в строке города
                $query = DB::select('id', 'name')
                        ->distinct('id')
                        ->from('cities')
                        ->where($this->_db_replace('`name`'), 'LIKE', '%' . $word . '%')
                        ->or_where($this->_db_replace('`genitive_name`'), 'LIKE', '%' . $word . '%')
                        ->or_where($this->_db_replace('`dativus_name`'), 'LIKE', '%' . $word . '%')
                        ->execute();

                $city_added = FALSE;

                foreach ($query as $q) {
                    $passed = $this->_set_search_param('cities', $q['id'], $word_index, array('name' => $q['name'], 'word' => $word));
                    if ($city_added === FALSE AND $passed === TRUE)
                        $city_added = $passed;
                }

                if ($city_added === TRUE)
                    continue;
            }

            // Поиск в строке марки автомобиля по названию и модели
            $query = DB::select(array('car_brands.id', 'id'), array($this->_db_replace('`car_brands`.`name_ru`'), 'name_ru'))
                    ->distinct('car_brands.id')
                    ->from('car_brands')
                    ->join(array('car_models', 'models'), 'LEFT')
                    ->on('car_brands.id', '=', 'models.car_id')
                    ->where($this->_db_replace('`car_brands`.`name`'), 'LIKE', $word . '%')
                    ->or_where($this->_db_replace('`car_brands`.`name_ru`'), 'LIKE', $word . '%')
                    ->or_where($this->_db_replace('`models`.`name`'), 'LIKE', $word . '%')
                    ->execute()
                    ->current();

            if ($query['id'] AND $this->_set_search_param('car_brands', $query['id'], $word_index, array('name' => $query['name_ru'], 'word' => $word)))
                continue;

            // Поиск услуги
            $query = DB::select(array('works.id', 'id'), array('works.name', 'work_name'), array('categories.name', 'category_name'))
                    ->distinct('works.id')
                    ->from('works')
                    ->join(array('work_categories', 'categories'), 'LEFT')
                    ->on('works.category_id', '=', 'categories.id')
                    ->where($this->_db_replace('`works`.`name`'), 'LIKE', '%' . $word . '%')
                    ->or_where($this->_db_replace('`categories`.`name`'), 'LIKE', '%' . $word . '%')
                    ->execute();
            $work_added = FALSE;
            foreach ($query as $q) {
                $passed = $this->_set_search_param('works', $q['id'], $word_index, array('work name' => $q['work_name'], 'category name' => $q['category_name'], 'word' => $word));
                if ($work_added === FALSE AND $passed)
                    $work_added = $passed;
            }
            if ($work_added === TRUE)
                continue;

            // Тип компании
            if ((strpos($word, 'сервис') !== FALSE OR strpos($word, 'автосервис') !== FALSE) AND $this->_set_search_param('company_types', Model_Service::AUTOSERVICE, $word_index, array('word' => $word)))
                continue;

            if ((strpos($word, 'магазин') !== FALSE OR strpos($word, 'автозапчаст') !== FALSE) AND $this->_set_search_param('company_types', Model_Service::SHOP, $word_index, array('word' => $word)))
                continue;

            // Поиск в строке округа
            $query = DB::select('id', 'name')
                    ->distinct('id')
                    ->from('okrug')
                    ->where('name', 'LIKE', '%' . $word . '%')
                    ->or_where($this->_db_replace('`full_name`'), 'LIKE', '%' . $word . '%')
                    ->or_where('abbreviation', 'LIKE', '%' . $word . '%')
                    ->execute();

            $district_added = FALSE;

            foreach ($query as $q) {
                $passed = $this->_set_search_param('districts', $q['id'], $word_index, array('name' => $q['name'], 'word' => $word));
                if ($district_added === FALSE AND $passed)
                    $district_added = $passed;
            }

            if ($district_added === TRUE)
                continue;

            /**
             * Для станций метро как минимум 5 символов
             * Поиск в строке станции метро
             */
            if (mb_strlen($word) > 5) {

                $query = DB::select('id', 'name')
                        ->distinct('id')
                        ->from('metro')
                        ->where($this->_db_replace('`name`'), 'LIKE', '%' . $word . '%')
                        ->execute();

                $metro_added = FALSE;
                foreach ($query as $q) {
                    $passed = $this->_set_search_param('districts', $q['id'], $word_index, array('name' => $q['name'], 'word' => $word));
                    if ($metro_added === FALSE AND $passed)
                        $metro_added = $passed;
                }
                if ($metro_added === TRUE)
                    continue;
            }


            $this->_set_search_param('text_params', $word, $word_index);
        }

        if ($this->_site_search_params['params_count'] < 1)
            return FALSE;

        /**
         * Начинаем выборку
         * Здесь указываются все параметры сгенерированные выше
         */
        // Выбираем поля для сопостовления их с параметрами
        $this->select(array('service.name', 'name'), array('service.address', 'address'), array('service.about', 'about'), array('service.type', 'type'), array('service.city_id', 'city_id'), array('service.district_id', 'district_id'), array('service.metro_id', 'metro_id'));
        $this->distinct('service.id');


        // Выборка по типу компаний
        $type_count = count($this->_site_search_params['company_types']);
        for ($i = 0; $i < $type_count; $i++) {

            if ($i < 1)
                $this->and_where_open();

            $this->or_where('service.type', '=', $this->_site_search_params['company_types'][$i]);

            if ($i >= $type_count - 1)
                $this->and_where_close();
        }


        // Выборка по городу
        $cities_count = count($this->_site_search_params['cities']);

        for ($i = 0; $i < $cities_count; $i++) {

            if ($i < 1)
                $this->and_where_open();

            $this->or_where('service.city_id', '=', $this->_site_search_params['cities'][$i]);

            if ($i >= $cities_count - 1)
                $this->and_where_close();
        }

        // Выборка по округу
        $districts_count = count($this->_site_search_params['districts']);

        for ($i = 0; $i < $districts_count; $i++) {

            if ($i < 1)
                $this->and_where_open();

            $this->or_where('service.district_id', '=', $this->_site_search_params['districts'][$i]);

            if ($i >= $districts_count - 1)
                $this->and_where_close();
        }

        // Выборка по станции метро
        $metro_count = count($this->_site_search_params['metro_stations']);

        for ($i = 0; $i < $metro_count; $i++) {

            if ($i < 1)
                $this->and_where_open();

            $this->or_where('service.metro_id', '=', $this->_site_search_params['metro_stations'][$i]);

            if ($i >= $metro_count - 1)
                $this->and_where_close();
        }

        /**
         * Выборка по марке автомобиля и услуге
         * Используется групповое условие, то есть И (марки авто и услуги)
         */
        $cars_count = count($this->_site_search_params['car_brands']);
        $works_count = count($this->_site_search_params['works']);

        if ($cars_count > 0 AND $works_count > 0)
            $this->and_where_open();

        for ($i = 0; $i < $cars_count; $i++) {

            if ($i < 1) {
                $this->join(array('services_cars', 'cars'), 'LEFT')
                        ->on('service.id', '=', 'cars.service_id');
                $this->and_where_open();
            }


            $this->or_where('cars.car_id', '=', $this->_site_search_params['car_brands'][$i]);

            if ($i >= $cars_count - 1)
                $this->and_where_close();
        }

        for ($i = 0; $i < $works_count; $i++) {
            if ($i < 1) {
                $this->join(array('services_works', 'works'), 'LEFT')
                        ->on('service.id', '=', 'works.service_id');
                $this->and_where_open();
            }


            $this->or_where('works.work_id', '=', $this->_site_search_params['works'][$i]);

            if ($i >= $works_count - 1)
                $this->and_where_close();
        }

        if ($cars_count > 0 AND $works_count > 0)
            $this->and_where_close();


        // Выборка по текстовым параметрам, название, описание, адрес
        $text_count = count($this->_site_search_params['text_params']);
        for ($i = 0; $i < $text_count; $i++) {
            if ($i < 1)
                $this->or_where_open();

            $this->or_where('service.name', 'LIKE', '%' . $this->_site_search_params['text_params'][$i]);
            $this->or_where('service.address', 'LIKE', '%' . $this->_site_search_params['text_params'][$i]);
            $this->or_where('service.about', 'LIKE', '%' . $this->_site_search_params['text_params'][$i]);

            if ($i >= $text_count - 1)
                $this->or_where_close();
        }


        $services = array();
        $shops = array();
        foreach ($this->find_all() as $company) {
            if ($company->type == Model_Service::AUTOSERVICE)
                $services[] = $company;
            if ($company->type == Model_Service::SHOP)
                $shops[] = $company;
        }
        return (object) array(
                    'company' => $this,
                    'shops' => $shops,
                    'services' => $services
        );

        //return $this;
    }

    /**
     * Генерация слов по которым найдена компания
     * @param $company
     * @return $this
     */
    public function get_words($company) {
        $words = array();

        foreach ($this->_word_index_relations['company_types'] as $value => $word_index) {
            if ($company->type == $value)
                $words[] = $this->_original_words[$word_index];
        }
        foreach ($this->_word_index_relations['cities'] as $value => $word_index) {
            if ($company->city_id == $value)
                $words[] = $this->_original_words[$word_index];
        }
        foreach ($this->_word_index_relations['districts'] as $value => $word_index) {
            if ($company->district_id == $value)
                $words[] = $this->_original_words[$word_index];
        }
        foreach ($this->_word_index_relations['metro_stations'] as $value => $word_index) {
            if ($company->metro_id == $value)
                $words[] = $this->_original_words[$word_index];
        }
        foreach ($this->_word_index_relations['car_brands'] as $value => $word_index) {
            if ($company->has('cars', $value))
                $words[] = $this->_original_words[$word_index];
        }
        foreach ($this->_word_index_relations['works'] as $value => $word_index) {
            if ($company->has('works', $value))
                $words[] = $this->_original_words[$word_index];
        }
        $words = array_unique($words);

        return View::factory('frontend/search/search_words')->set('words', $words);
    }

    /**
     * Убираем тире, скобки и пробелы для полей базы
     * @param $field
     * @return Database_Expression
     */
    private function _db_replace($field) {
        return DB::expr("REPLACE(REPLACE(REPLACE(REPLACE(" . (string) $field . ", '-', ''), ' ', ''), ')', ''), '(', '')");
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules() {
        if ($this->disable_validation)
            return array();

        $this->validation()->bind(':city_id', $this->city_id);

        return array(
            'name' => array(
                array('not_empty'),
                array(array($this, 'unique'), array('name', ':value'))
            ),
            'city_id' => array(
                array('not_empty'),
                array(array('Model_City', 'available'), array(':value'))
            ),
            'district_id' => array(
                // Проверка на корректность id округа
                array(array('Model_District', 'available'), array(':value', ':city_id'))
            ),
            'metro_id' => array(
                // Проверка на корректность id станции метро
                array(array('Model_Metro', 'available'), array(':value', ':city_id'))
            ),
            'org_type' => array(
                array('not_empty'),
                array(array('Model_OrgType', 'available'), array(':value'))
            ),
            'address' => array(array('not_empty')),
            'phone' => array(
                array('not_empty')
            ),
            'code' => array(
                array('not_empty'),
                array('digit')
            ),
            'date_create' => array(array('not_empty')),
            'inn' => array(array('digit')),
            'discount_id' => array(
                array('not_empty')
            )
        );
    }

    public function filters() {
        return array(
            'name' => array(array('trim')),
            'inn' => array(array('trim')),
            'address' => array(array('trim')),
            'org_type' => array(array('trim')),
            'company_type' => array(array('trim')),
            'phone' => array(array('trim')),
            'fax' => array(array('trim')),
            'email' => array(array('trim')),
            'about' => array(array('trim')),
            'director_name' => array(array('trim')),
            'contact_person' => array(array('trim')),
            'site' => array(array('trim')),
            'phone' => array(array('trim')),
            'city_id' => array(array('trim')),
            'date_create' => array(array('trim')),
            'date_edited' => array(array('trim')),
        );
    }

    /**
     * Параметры поиска
     * @param $name
     * @param $operator
     * @param $value
     * @param string $type
     * @return void
     */
    public function set_params(Array $params = NULL) {
        $this->_search_params = $params;
        return $this;
    }

    public function set_has_items(Array $items = NULL) {
        $this->_has_items = $items;
        return $this;
    }

    /**
     * Указать искомые авто или услуги
     * @param $name
     * @param array $ids
     * @return void
     */
    public function set_has_item($name, $ids = array()) {
        $this->_has_items[] = array(
            'name' => $name,
            'ids' => $ids
        );
    }

    /**
     * Возвращает найденные сервисы
     * @return Model_Service
     */
    public function get_services_2() {
        $this->distinct('id')->order_by('active', 'DESC')->order_by('date_edited', 'DESC')->where('type', '=', 1);
        foreach ($this->_search_params as $param) {
            if ($param['value']) {
                $this->and_where($param['field'], $param['op'], $param['value']);
            }
        }
        $config = Kohana::$config->load('settings.services_items');
        foreach ($this->_has_items as $name => $item) {
            if (isset($config[$name])) {
                $this->join(array($config[$name]['table_name'], $name))
                        ->on('service.id', '=', $name . '.service_id');
                if (is_array($item['ids']) AND ! empty($item['ids']))
                    $this->and_where($name . '.' . $config[$name]['key'], 'in', $item['ids']);
            }
        }
        return $this;
    }

    public function get_address() {
        $addr = 'г. ' . $this->city->name;
        if ($this->district_id) {
            $addr .= (trim($this->district->abbreviation)) ? ', ' . $this->district->abbreviation : ', ' . $this->district->full_name;
        }
        $addr .= ', ' . $this->address;
        if ($this->metro_id) {
            $addr .= ', рядом со ст. метро ' . $this->metro->name;
        }
        return $addr;
    }

    public function get_car_brands() {
        $cars = array();
        foreach ($this->cars->find_all() as $car) {
            $cars[] = $car->get_car_name(TRUE);
        }
        return $cars;
    }

    /**
     * Получение кол-ва страниц
     * @param $services_count
     * @return float
     */
    public function get_pages_count($services_count) {
        $items_on_page = Kohana::$config->load('settings.pagination.items_on_page');
        return ceil($services_count / $items_on_page);
    }

    public function get_offset_limit($pages_count, $page = 1) {
        $result['limit'] = Kohana::$config->load('settings.pagination.items_on_page');
        if ($page >= 1 AND $pages_count >= $page) {
            $result['offset'] = ($page - 1) * $result['limit'];
        } else {
            $result['offset'] = 0;
            $page = 1;
        }
    }

    public function check_org_type($value) {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('org_types')->where('id', '=', $value)->execute()->get('total_count');
    }

    private function check_cars_for_search($str) {
        $query = DB::select('car_brands.id')
                ->from('car_brands')
                ->join(array('car_models', 'models'))
                ->on('models.car_id', '=', 'car_brands.id')
                ->where('car_brands.name', 'LIKE', '%' . $str . '%')
                ->or_where('car_brands.name_ru', 'LIKE', '%' . $str . '%')
                ->or_where('models.name', 'LIKE', '%' . $str . '%')
                ->execute();

        $cars = array();
        foreach ($query as $q) {
            $cars[] = $q['id'];
        }
        return $cars;
    }

    private function check_district_for_search($str) {
        return DB::select('id')
                        ->from('okrug')
                        ->where('name', 'LIKE', '%' . $str . '%')
                        ->or_where('abbreviation', 'LIKE', '%' . $str . '%')
                        ->execute()->get('id');
    }

    private function check_metro_for_search($str) {
        return DB::select('id')
                        ->from('metro')
                        ->where('name', 'LIKE', '%' . $str . '%')
                        ->execute()->get('id');
    }

    private function check_work_for_search($str) {
        $query = DB::select('works.id')
                ->from('works')
                ->join(array('work_categories', 'category'))
                ->on('works.category_id', '=', 'category.id')
                ->where('works.name', 'LIKE', '%' . $str . '%')
                ->or_where('category.name', 'LIKE', '%' . $str . '%')
                ->execute();
        $works = array();
        foreach ($query as $q) {
            $works[] = $q['id'];
        }
        return $works;
    }

    private function check_city_for_search($str) {
        return DB::select('id')
                        ->from('cities')
                        ->where('name', 'LIKE', '%' . $str . '%')
                        ->execute()->get('id');
    }

    public function get_services_as_array($params = NULL) {
        if ($params AND is_array($params)) {
            foreach ($params as $field => $key) {
                $this->and_where($field, '=', $key);
            }
        }
        $services = array();
        foreach ($this->order_by('name', 'ASC')->find_all() as $service) {
            $services[$service->id] = $service->name;
        }
        return $services;
    }

    public function get_all_services() {
        $services = array();
        foreach ($this->find_all() as $service) {
            $services[$service->id] = $service;
        }
        return $services;
    }

    /**
     * Получаем из Массива ID сервисов массив пользователей "хозяев"
     * @param array $services
     * @return array
     */
    public static function get_user_ids($params = array()) {
        $query = DB::select(array('user.id', 'user_id'), array('services.id', 'service_id'))->from('services')
                ->join(array('users', 'user'))
                ->on('services.user_id', '=', 'user.id')

                //->where('services.id', 'in', $services)
                ->execute();
        $user_list = array();
        foreach ($query as $service) {
            $user_list[$service['service_id']] = $service['user_id'];
        }
        return $user_list;
    }

    // Получение полного названия
    public function get_full_name($org_type = TRUE) {
        if ($org_type)
            return __('company_type_' . $this->type) . ' ' . $this->orgtype->name . ' &laquo;' . $this->name . '&raquo;';
        else
            return __('company_type_' . $this->type) . ' ' . $this->name;
    }

    // Получение имени
    public function get_name($mode = 1) {
        switch ($mode) {
            // Witch company type, org type
            case 1:
                return __('company_type_' . $this->type) . ' ' . $this->orgtype->name . ' &laquo;' . $this->name . '&raquo;';
                break;
            // Witch company type
            case 2:
                return __('company_type_' . $this->type) . ' ' . $this->name;
                break;
            // Witch org type
            case 3:
                return $this->orgtype->name . ' &laquo;' . $this->name . '&raquo;';
                break;
        }
    }

    public static function available($id) {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('services')->where('id', '=', $id)->execute()->get('total_count');
    }

    /**
     * Получение текста скидки
     * @return string
     */
    public function get_stock_text() {
        return (trim($this->coupon_text)) ? $this->coupon_text : __('coupon_standart_text', array(':percent' => $this->discount->percent, ':service' => $this->name));
    }
    
    public function get_cities($post) {
        $result = array();
        
        $city_array = DB::select('name')
                ->from('cities')
                ->where('name', 'LIKE', '%'.mysql_real_escape_string($post['str']).'%')
                ->execute();
        
        foreach ($city_array as $city) {
            $result[] = $city['name'];
        }
        
        return $result;
    }
}
